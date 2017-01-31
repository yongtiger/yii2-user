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
 * Resend Form Model
 *
 * @package yongtiger\user\models
 * @property string $email
 * @property string $verifyCode
 */
class ResendForm extends Model
{
    /**
     * @var string email
     */
    public $email;

    /**
     * @var string verifyCode
     */
    public $verifyCode; ///[Yii2 uesr:verifycode]

    /**
     * @var \yongtiger\user\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'exist',
                'targetClass' => '\yongtiger\user\models\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => Module::t('user', 'There is no user with such email.')
            ],
        ];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableSignupWithCaptcha) {
            $rules = array_merge($rules, [
                ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
                ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
                ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/registration/captcha'],
            ]);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels['email'] = Module::t('user', 'Email');

        if (Yii::$app->getModule('user')->enableSignupWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('user', 'Verification Code');  ///[Yii2 uesr:verifycode]
        }

        return $attributeLabels;
    }

    /**
     * Finds user by username or email.
     *
     * @return User|null User object or null
     */
    public function getUser()
    {
        if ($this->_user === null) {

            $this->_user = User::findOne([
                'status' => User::STATUS_INACTIVE,
                'email' => $this->email,
            ]);
        }
        return $this->_user;
    }

    /**
     * Set user.
     *
     * @param User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * Resend email activation key.
     *
     * @return boolean true if sent successfully
     */
    public function resend($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if ($user = $this->getUser()) {

            $user ->generateAuthKey();
            $user ->activation_key = SecurityHelper::generateExpiringRandomKey(Yii::$app->getModule('user')->signupWithEmailActivationExpire);

            if ($user ->save(false)) {

                ///send activation email
                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => Yii::$app->getModule('user')->signupWithEmailActivationComposeHtml, 'text' => Yii::$app->getModule('user')->signupWithEmailActivationComposeText],
                        ['user' => $user]
                    )
                    ->setFrom(Yii::$app->getModule('user')->signupWithEmailActivationSetFrom)
                    ->setTo($this->email)
                    ->setSubject(Module::t('user', 'Activation mail of the registration from ') . Yii::$app->name)
                    ->send();

                Yii::$app->session->addFlash('success', Module::t('user', 'An activation link has been sent to the email address you entered.'));

                return true;
            }
        }

        Yii::$app->session->addFlash('error', Module::t('user', 'Resend activation email failed! Please try again.'));

        return false;
    }
}