<?php ///[Yii2 uesr]

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
use yongtiger\user\Models\User;
use yongtiger\user\Module;

/**
 * Password Reset Request Form Msodel
 *
 * @package yongtiger\user\models
 * @property string $email
 * @property string $verifyCode
 */
class PasswordResetRequestForm extends Model
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
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\yongtiger\user\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Module::t('user', 'There is no user with such email.')
            ],
        ];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableRequestPasswordResetWithCaptcha) {
            $rules = array_merge($rules, [
                ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
                ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
                ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/recovery/captcha'],
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

        if (Yii::$app->getModule('user')->enableRequestPasswordResetWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('user', 'Verification Code');  ///[Yii2 uesr:verifycode]
        }

        return $attributeLabels;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        ///[Yii2 uesr:mail]
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => Yii::$app->getModule('user')->requestPasswordResetComposeHtml, 'text' => Yii::$app->getModule('user')->requestPasswordResetComposeText],
                ['user' => $user]
            )
            ->setFrom(Yii::$app->getModule('user')->requestPasswordResetSetFrom)
            ->setTo($this->email)
            ->setSubject(Module::t('user', 'Password reset for ') . Yii::$app->name)
            ->send();
    }
}
