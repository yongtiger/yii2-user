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
use yii\base\InvalidParamException;
use yii\db\IntegrityException;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/**
 * Password Reset Form Model
 *
 * @package yongtiger\user\models
 * @property string $password
 * @property string $resetPassword
 * @property string $verifyCode
 */
class ResetPasswordForm extends Model
{
    /**
     * @var string password
     */
    public $password;

    /**
     * @var string repassword
     */
    public $repassword; ///[Yii2 uesr:repassword]

    /**
     * @var string verifyCode
     */
    public $verifyCode; ///[Yii2 uesr:verifycode]

    /**
     * @var \yongtiger\user\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Module::t('user', 'Password reset token cannot be blank.'));
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Module::t('user', 'Wrong password reset token.'));
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  [
            ///[Yii2 uesr:repassword]
            [['password','repassword'],'required'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword','compare','compareAttribute'=>'password','message' => Module::t('user', 'The two passwords do not match.')],
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
        $attributeLabels['password'] = Module::t('user', 'Password');
        $attributeLabels['repassword'] = Module::t('user', 'Repeat Password');  ///[Yii2 uesr:repassword]

        if (Yii::$app->getModule('user')->enableRequestPasswordResetWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('user', 'Verification Code');  ///[Yii2 uesr:verifycode]
        }

        return $attributeLabels;
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        if ($user->save(false)) {

            ///[Yii2 uesr:verify]
            $user->verify->password_verified_at = time();
            $user->verify->email_verified_at = time();
            if (!$user->verify->save(false)) {
                throw new IntegrityException();
            }

            return true;
        }

        return false;
    }
}
