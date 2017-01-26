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
use yongtiger\user\models\User;
use yongtiger\user\Module;
use yongtiger\user\helpers\SecurityHelper;

/**
 * Signup Form Model
 *
 * @package yongtiger\user\models
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $repassword
 * @property string $verifyCode
 */
class SignupForm extends Model
{
    ///[Yii2 uesr:activation via email:signup]signup events
    const EVENT_BEFORE_SIGNUP = 'beforeSignup';
    const EVENT_AFTER_SIGNUP = 'afterSignup';

    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_OAUTH = 'oauth';

    public $username;
    public $email;
    public $password;
    public $repassword; ///[Yii2 uesr:repassword]
    public $verifyCode; ///[Yii2 uesr:verifycode]

    ///[Yii2 uesr:activation via email:signup]signup events
    /**
     * @var \yongtiger\user\models\User
     */
    private $_user;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['username', 'password', 'repassword', 'email', 'verifyCode'];
        $scenarios[self::SCENARIO_OAUTH] = ['username', 'email'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  [
            [['password'], 'required'],
            [['password'], 'string', 'min' => 6],
        ];

        if (Yii::$app->getModule('user')->enableSignupWithUsername) {
            $rules = array_merge($rules, [
                ['username', 'required'],
                ['username', 'trim'],
                ['username', 'filter', 'filter' => function ($value) {
                    return preg_replace('/[^(\x{4E00}-\x{9FA5})\w]/iu', '', $value);
                }],
                ['username', 'string', 'min' => 2, 'max' => 32],

                ///[Yii2 uesr:username]User name verification
                //The unicode range of Chinese characters is: 0x4E00~0x9FA5. This range also includes Chinese, Japanese and Korean characters
                //  u   Indicates to match by unicode (utf-8), mainly for multi-byte characters such as Chinese characters
                //  \x  Ignore whitespace
                //[(\x{4E00}-\x{9FA5})a-zA-Z]+  The character starts with a Chinese character or letter and appears 1 to n times
                //[(\x{4E00}-\x{9FA5})\w]*      Chinese characters underlined alphabet, there 0-n times
                ['username', 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})\w]*$/u', 'message' => Module::t('user', 'The username only contains letters ...')],

                ['username', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This username has already been taken.')],
            ]);
        }

        if (Yii::$app->getModule('user')->enableSignupWithEmail) {
            $rules = array_merge($rules, [
                ['email', 'trim'],
                ['email', 'required'],
                ['email', 'string', 'max' => 255],
                ['email', 'email'],
                ['email', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This email address has already been taken.')],
            ]);
        }

        ///[Yii2 uesr:repassword]
        if (Yii::$app->getModule('user')->enableSignupWithRepassword) {
            $rules = array_merge($rules, [
                [['repassword'], 'required'],
                [['repassword'], 'string', 'min' => 6],
                ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => Module::t('user', 'The two passwords do not match.')],
            ]);
        }

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
        $attributeLabels['password'] = Module::t('user', 'Password');

        if (Yii::$app->getModule('user')->enableSignupWithUsername) {
            $attributeLabels['username'] = Module::t('user', 'Username');
        }

        if (Yii::$app->getModule('user')->enableSignupWithEmail) {
            $attributeLabels['email'] = Module::t('user', 'Email');
        }

        if (Yii::$app->getModule('user')->enableSignupWithRepassword) {
            $attributeLabels['repassword'] = Module::t('user', 'Repeat Password');   ///[Yii2 uesr:repassword]
        }

        if (Yii::$app->getModule('user')->enableSignupWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('user', 'Verification Code');  ///[Yii2 uesr:verifycode]
        }

        return $attributeLabels;
    }

    ///[Yii2 uesr:activation via email:signup]signup events
    /**
     * Finds user by [[username]].
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = new User();

            if (Yii::$app->getModule('user')->enableSignupWithUsername) {
                $this->_user->username = $this->username;
            }

            if (Yii::$app->getModule('user')->enableSignupWithEmail) {
                $this->_user->email = $this->email;
            }

            $this->_user->setPassword($this->password);
            $this->_user->generateAuthKey();
        }
        return $this->_user;
    }

    ///[Yii2 uesr:activation via email:signup]
    /**
     * Signs user up.
     *
     * @param boolean $runValidation whether to perform validation (calling [[validate()]])
     * @return User|null|false the saved model or null if saving fails, false if validation fails
     */
    public function signup($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if ($this->beforeSignup()) {

            // ...custom code here...
            if ($this->getUser()->save(false)) {

                $this->afterSignup();
                return $this->getUser();
            }else{
                return null;
            }

        }
        return false;
    }

    ///[Yii2 uesr:activation via email:signup]signup events
    /**
     * This method is called before signup.
     *
     * The default implementation will trigger the [[EVENT_BEFORE_SIGNUP]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * You can get the user identity by $this->getUser() in your overrided method.
     *
     * ```php
     * public function beforeSignup()
     * {
     *     if (parent::beforeSignup()) {
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
     * @return bool whether the user should continue to signup
     */
    protected function beforeSignup()
    {
        $event = new ModelEvent();
        $this->trigger(self::EVENT_BEFORE_SIGNUP, $event);

        if ($event->isValid) {

            // ...custom code here...
            ///[Yii2 uesr:activation via email:signup]signup events
            if (Yii::$app->getModule('user')->enableSignupWithEmail && Yii::$app->getModule('user')->enableSignupWithEmailActivation) {
                $this->getUser()->status = User::STATUS_INACTIVE;
                $this->getUser()->activation_key = SecurityHelper::generateExpiringRandomKey(Yii::$app->getModule('user')->signupWithEmailActivationExpire);
            }

        }
        return $event->isValid;
    }

    ///[Yii2 uesr:activation via email:signup]signup events
    /**
     * This method is called after the user is successfully signup.
     *
     * The default implementation will trigger the [[EVENT_AFTER_SIGNUP]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * You can get the user identity by $this->getUser() in your overrided method.
     *
     * ```php
     * public function afterSignup()
     * {
     *     // ...custom code here...
     *
     *     $this->trigger(self::EVENT_AFTER_SIGNUP, new ModelEvent());
     * }
     * ```
     *
     */
    protected function afterSignup()
    {
        // ...custom code here...
        if (Yii::$app->getModule('user')->enableSignupWithEmail && Yii::$app->getModule('user')->enableSignupWithEmailActivation) {
            ///[Yii2 uesr:activation via email:signup]send activation email
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => Yii::$app->getModule('user')->signupWithEmailActivationComposeHtml, 'text' => Yii::$app->getModule('user')->signupWithEmailActivationComposeText],
                    ['user' => $this->getUser()]
                )
                ->setFrom(Yii::$app->getModule('user')->signupWithEmailActivationSetFrom)
                ->setTo($this->email)
                ->setSubject(Module::t('user', 'Activation mail of the registration from ') . Yii::$app->name)
                ->send();

            Yii::$app->session->addFlash('warning', Module::t('user', 'Please check your email [{youremail}] to activate your account.', ['youremail' => $this->email]));
        }

        $this->trigger(self::EVENT_AFTER_SIGNUP, new ModelEvent());
    }
}
