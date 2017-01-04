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
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $verifyCode; ///[Yii2 uesr:verifycode]

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\yongtiger\user\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Module::t('user', 'There is no user with such email.')
            ],

            ///[Yii2 uesr:verifycode]
            ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
            ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/registration/captcha'], 
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
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
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
                ['html' => '@yongtiger/user/mail/passwordResetToken-html', 'text' => '@yongtiger/user/mail/passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject(Module::t('user', 'Password reset for ') . Yii::$app->name)
            ->send();
    }
}
