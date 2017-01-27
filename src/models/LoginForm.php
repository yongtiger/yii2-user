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
use yii\base\ModelEvent;
use yii\helpers\Html;
use yongtiger\user\Module;

/**
 * Login Form Model
 *
 * @package yongtiger\user\models
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $rememberMe
 * @property string $verifyCode
 * @property User $user
 */
class LoginForm extends Model
{
    ///[Yii2 uesr:activation via email:login]login events
    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    public $username;
    public $email;
    public $password;
    public $rememberMe;
    public $verifyCode; ///[Yii2 uesr:verifycode]

    public $usernameOrEmail;

    /**
     * @var \yongtiger\user\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->rememberMe = Yii::$app->user->enableAutoLogin;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  [];

        ///[Yii2 uesr:login with username or email]
        if (Yii::$app->getModule('user')->enableLoginWithUsername && Yii::$app->getModule('user')->enableLoginWithEmail) {
                $rules = array_merge($rules, [
                    ['usernameOrEmail', 'required'],
                ]);
        } else {
            if (Yii::$app->getModule('user')->enableLoginWithUsername) {
                $rules = array_merge($rules, [
                    ['username', 'required'],
                ]);
            }

            if (Yii::$app->getModule('user')->enableLoginWithEmail) {
                $rules = array_merge($rules, [
                    ['email', 'required'],
                    ['email', 'email'],
                ]);
            }
        }


        if (Yii::$app->getModule('user')->enableLoginWithUsername || Yii::$app->getModule('user')->enableLoginWithEmail) {
            $rules = array_merge($rules, [
                // password is required
                ['password', 'required'],
                // password is validated by validatePassword()
                ['password', 'validatePassword'],
            ]);

            ///[Yii2 uesr:verifycode]
            if (Yii::$app->getModule('user')->enableLoginWithCaptcha) {
                $rules = array_merge($rules, [
                    ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
                    ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
                    ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                    ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/security/captcha'],
                ]);
            }
        }

        if (Yii::$app->user->enableAutoLogin) {
            $rules = array_merge($rules, [
                ['rememberMe', 'boolean'],
            ]);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        ///[Yii2 uesr:login with username or email]
        if (Yii::$app->getModule('user')->enableLoginWithUsername && Yii::$app->getModule('user')->enableLoginWithEmail) {
            $attributeLabels['usernameOrEmail'] = Module::t('user', 'Username or Email');
        } else {
            if (Yii::$app->getModule('user')->enableLoginWithUsername) {
                $attributeLabels['username'] = Module::t('user', 'Username');
            }

            if (Yii::$app->getModule('user')->enableLoginWithEmail) {
                $attributeLabels['email'] = Module::t('user', 'Email');
            }
        }

        if (Yii::$app->getModule('user')->enableLoginWithUsername || Yii::$app->getModule('user')->enableLoginWithEmail) {
            $attributeLabels['password'] = Module::t('user', 'Password');
        }

        if (Yii::$app->user->enableAutoLogin) {
            $attributeLabels['rememberMe'] = Module::t('user', 'Remember me');
        }

        if (Yii::$app->getModule('user')->enableLoginWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('user', 'Verification Code');  ///[Yii2 uesr:verifycode]
        }

        return $attributeLabels;
    }

    /**
     * Validates the password.
     *
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Module::t('user', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Finds user by username or email.
     *
     * @return User|null User object or null
     */
    public function getUser()
    {
        if ($this->_user === null) {

            $condition['status'] = [User::STATUS_ACTIVE, User::STATUS_INACTIVE];

            if (Yii::$app->getModule('user')->enableLoginWithUsername) {
                $condition['username'] = $this->username;
            }

            if (Yii::$app->getModule('user')->enableLoginWithEmail) {
                $condition['email'] = $this->email;
            }

            $this->_user = User::findOne($condition);
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
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login($runValidation = true)
    {
        ///[Yii2 uesr:activation via email:login]login events
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if ($this->beforeLogin()) {

            // ...custom code here...
            if (Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0)) {

                $this->afterLogin();
                return true;
            }

        }

        Yii::$app->session->addFlash('error', Module::t('user', 'Login failed!'));
        return false;
    }

    ///[Yii2 uesr:activation via email:login]login events
    /**
     * This method is called before logging in a user.
     *
     * The default implementation will trigger the [[EVENT_BEFORE_LOGIN]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * You can get the user identity by $this->getUser() in your overrided method.
     *
     * ```php
     * public function beforeLogin()
     * {
     *     if (parent::beforeLogin()) {
     *
     *         // ...custom code here...
     *         $this->getUser();
     *
     *         return true;
     *     } else {
     *         return false;
     *     }
     * }
     * ```
     *
     * @return bool whether the user should continue to be logged in
     */
    protected function beforeLogin()
    {
        $event = new ModelEvent();
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($event->isValid) {

            // ...custom code here...
            if (empty($this->getUser())) {
                Yii::$app->session->addFlash('error', Module::t('user', 'Your account is invalid!'));
                $event->isValid = false;
            }

            ///[Yii2 uesr:activation via email:login]
            if ($this->getUser()->status == User::STATUS_INACTIVE) {
                Yii::$app->session->addFlash('warning',
                    Module::t('user',
                        'Your account is not activated! Click [{resend}] an activation Email.',
                        ['resend'=>Module::t('user', Html::a(Module::t('user', 'Resend'), ['registration/resend']))]
                    )
                );
                $event->isValid = false;
            }

        }
        return $event->isValid;
    }

    /**
     * This method is called after the user is successfully logged in.
     *
     * The default implementation will trigger the [[EVENT_AFTER_LOGIN]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * You can get the user identity by $this->getUser() in your overrided method.
     *
     * ```php
     * public function afterLogin()
     * {
     *     // ...custom code here...
     *
     *     $this->trigger(self::EVENT_AFTER_LOGIN, new ModelEvent());
     * }
     * ```
     *
     */
    protected function afterLogin()
    {
        $this->trigger(self::EVENT_AFTER_LOGIN, new ModelEvent());
    }
}
