<?php ///[Yii2 uesr:activation via email:Resend]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\models;

use Yii;
use yii\base\Model;
use yongtiger\user\Module;
use yongtiger\user\helpers\SecurityHelper;

/**
 * Resend form model
 *
 * @package yongtiger\user\models
 * @property string $email
 */
class ResendForm extends Model
{
    public $email;
    public $verifyCode; ///[Yii2 uesr:verifycode]

    /**
     * @var User User instance
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // E-mail
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 55],
            [
                'email',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                    $query->andWhere(['status' => User::STATUS_INACTIVE]);
                }
            ],

            ///[Yii2 uesr:verifycode]
            ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
            ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
            ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
            ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/recovery/captcha'], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Module::t('user', 'Email'),
            'verifyCode' => Module::t('user', 'Verification Code'),  ///[Yii2 uesr:verifycode]
        ];
    }

    /**
     * Resend email activation key.
     *
     * @return boolean true if sent successfully
     */
    public function resend($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {

            ///遍历输出errors二维数组 @see http://www.yiiframework.com/doc-2.0/yii-base-model.html#$errors-detail
            foreach ($this->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    Yii::$app->session->setFlash('danger', $error);
                }
            }

            return false;
        }

        $this->_user = User::findByEmail($this->email);
        if ($this->_user !== null) {

            $this->_user->generateAuthKey();
            $this->_user->activation_key = SecurityHelper::generateExpiringRandomKey(Yii::$app->getModule('user')->activateWithin);

            if ($this->_user->save(false)) {

                ///send activation email
                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => '@yongtiger/user/mail/activationKey-html', 'text' => '@yongtiger/user/mail/activationKey-text'],
                        ['user' => $this->_user]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject(Module::t('user', 'Activation mail of the registration from ') . Yii::$app->name)
                    ->send();

                Yii::$app->session->setFlash('success', Module::t('user', 'An activation link has been sent to the email address you entered.'));

                return true;
            }
        }

        Yii::$app->session->setFlash('danger', Module::t('user', 'Resend activation email failed. Please try again!'));

        return false;
    }
}