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
use yii\db\IntegrityException;
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
 * @property \yongtiger\user\models\User $user
 */
class LoginForm extends Model
{
    ///[Yii2 uesr:activation via email:login]login events
    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    /**
     * @var string username
     */
    public $username;

    /**
     * @var string email
     */
    public $email;

    /**
     * @var string password
     */
    public $password;

    /**
     * @var string remember me
     */
    public $rememberMe;

    /**
     * @var string verifyCode
     */
    public $verifyCode; ///[Yii2 uesr:verifycode]

    /**
     * @var string username or email
     */
    public $usernameOrEmail;    ///[Yii2 uesr:login with username or email]

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
        if (Yii::$app->getModule('user')->enableLoginWithUsername && Yii::$app->getModule('user')->enableLoginWithEmail || !empty(Yii::$app->request->getBodyParam('LoginForm')['usernameOrEmail'])) {
            $attributeLabels['usernameOrEmail'] = Module::t('message', 'Username or Email');
        } else {
            if (Yii::$app->getModule('user')->enableLoginWithUsername) {
                $attributeLabels['username'] = Module::t('message', 'Username');
            }

            if (Yii::$app->getModule('user')->enableLoginWithEmail) {
                $attributeLabels['email'] = Module::t('message', 'Email');
            }
        }

        if (Yii::$app->getModule('user')->enableLoginWithUsername || Yii::$app->getModule('user')->enableLoginWithEmail) {
            $attributeLabels['password'] = Module::t('message', 'Password');
        }

        if (Yii::$app->user->enableAutoLogin) {
            $attributeLabels['rememberMe'] = Module::t('message', 'Remember me');
        }

        if (Yii::$app->getModule('user')->enableLoginWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('message', 'Verification Code');  ///[Yii2 uesr:verifycode]
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
                $this->addError($attribute, Module::t('message', 'Incorrect username or password.'));
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
     * This method is called in `SecurityController::authenticate()`.
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
     * @param bool $runValidation whether to perform validation (calling [[validate()]])
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
        $this->trigger(static::EVENT_BEFORE_LOGIN, $event);

        if ($event->isValid) {

            // ...custom code here...
            if (empty($this->getUser())) {
                Yii::$app->session->addFlash('error', Module::t('message', 'Your account is invalid!'));
                $event->isValid = false;
            } else if ($this->getUser()->status == User::STATUS_INACTIVE) {
                ///[v0.18.2 (CHG# \models\LoginForm.php:beforeLogin():enableSignupWithEmailActivation)]
                $msg = Module::t('message', 'Your account is not activated!');
                if (Yii::$app->getModule('user')->enableSignupWithEmailActivation) {
                    $msg .= '\n' . Module::t('message', 'Click [{resend}] an activation Email.',
                        ['resend'=>Module::t('message', Html::a(Module::t('message', 'Resend'), ['token/send-token', 'type' => 'activation']))]
                    );
                } else {
                    $msg .= '\n' . Module::t('message', 'Signup with email activation is not enabled!');
                    $msg .= '\n' . Module::t('message', 'Please contact to the administrator.');
                }
                ///[Yii2 uesr:activation via email:login]
                Yii::$app->session->addFlash('warning', $msg);
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
     *     $this->getUser();
     *
     *     parent::afterLogin();
     * }
     * ```
     *
     */
    protected function afterLogin()
    {
        // ...custom code here...
        ///[Yii2 uesr:status]
        if (!$status = $this->getUser()->getStatus()->one()) {  ///avoid conflict with the `status` field in `user` table.
            $status = new Status(['user_id' => $this->getUser()->id]);
        } else {
            $status->user_id = $this->getUser()->id;
        }
        ///massive assignment @see http://www.yiiframework.com/doc-2.0/guide-structure-models.html#massive-assignment
        $status->attributes = [
            'last_login_ip' => Yii::$app->getRequest()->getUserIP(),
            'last_login_at' => time(),
        ];
        if (!$status->save(false)) {
            throw new IntegrityException();
        }

        ///[Yii2 uesr:count]
        if (!$count = $this->getUser()->getCount()->one()) {
            $count = new Count(['user_id' => $this->getUser()->id]);
        } else {
            $count->user_id = $this->getUser()->id;
        }
        ///massive assignment @see http://www.yiiframework.com/doc-2.0/guide-structure-models.html#massive-assignment
        $count->attributes = [
            'login_count' => ++$count->login_count,
        ];
        if (!$count->save(false)) {
            throw new IntegrityException();
        }

        $this->trigger(static::EVENT_AFTER_LOGIN, new ModelEvent());
    }
}
