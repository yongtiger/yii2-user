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
use yongtiger\user\helpers\SecurityHelper;

/**
 * Password Reset Request Form Msodel
 *
 * @package yongtiger\user\models
 * @property string $email
 * @property string $verifyCode
 * @property \yongtiger\user\models\User $user read-only user
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
     * @var \yongtiger\user\models\User
     */
    private $_user;

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
     * Finds user by email.
     *
     * @return User|null User object or null
     */
    public function getUser()
    {
        if ($this->_user === null) {

            $this->_user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);
        }
        return $this->_user;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        if ($user = $this->getUser()) {

            if (($validDuration = SecurityHelper::getValidDuration($user->password_reset_token)) > 0) {
                Yii::$app->session->addFlash('error', Module::t('user', 'Please do not send email repeatedly! Try again in {valid-duration}.', ['valid-duration' => Yii::$app->formatter->asDuration($validDuration)]));
                return false;
            } else {
                $user->generatePasswordResetToken();
                if ($user->save()) {
                    Yii::$app->session->addFlash('success', Module::t('user', 'Check your email for further instructions.'));
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
        }
        Yii::$app->session->addFlash('error', Module::t('user', 'The email address for resetting the password is invalid!'));
        return false;
    }
}
