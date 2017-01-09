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
 * Signup form model
 *
 * @package yongtiger\user\models
 * @property string $username
 * @property string $password
 * @property string $repassword     ///[Yii2 uesr:repassword]
 * @property string $verifyCode     ///[Yii2 uesr:verifycode]
 */
class SignupForm extends Model
{
    ///[Yii2 uesr:activation via email:signup]signup events
    const EVENT_BEFORE_SIGNUP = 'beforeSignup';
    const EVENT_AFTER_SIGNUP = 'afterSignup';

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ///[Yii2 uesr:username]User name verification
            //The unicode range of Chinese characters is: 0x4E00~0x9FA5. This range also includes Chinese, Japanese and Korean characters
            //  u   Indicates to match by unicode (utf-8), mainly for multi-byte characters such as Chinese characters
            //  \x  Ignore whitespace
            //[(\x{4E00}-\x{9FA5})a-zA-Z]+  The character starts with a Chinese character or letter and appears 1 to n times
            //[(\x{4E00}-\x{9FA5})\w]*      Chinese characters underlined alphabet, there 0-n times
            ['username', 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})\w]*$/u', 'message' => Module::t('user', 'The username only contains letters ...')],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This email address has already been taken.')],

            ///[Yii2 uesr:repassword]
            [['password','repassword'],'required'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword','compare','compareAttribute'=>'password','message' => Module::t('user', 'The two passwords do not match.')],

            ///[Yii2 uesr:verifycode]
            ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
            ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
            ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
            ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/registration/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('user', 'Username'),
            'email' => Module::t('user', 'Email'),
            'password' => Module::t('user', 'Password'),
            'repassword' => Module::t('user', 'Repeat Password'),   ///[Yii2 uesr:repassword]
            'verifyCode' => Module::t('user', 'Verification Code'),  ///[Yii2 uesr:verifycode]
        ];
    }

    ///[Yii2 uesr:activation via email:signup]signup events
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = new User();
            $this->_user->username = $this->username;
            $this->_user->email = $this->email;
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
            if (Yii::$app->getModule('user')->enableActivation) {
                $this->getUser()->status = User::STATUS_INACTIVE;
                $this->getUser()->activation_key = SecurityHelper::generateExpiringRandomKey(Yii::$app->getModule('user')->activateWithin);
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
        ///[Yii2 uesr:activation via email:signup]
        $successText = Module::t('user',
            'Successfully registered [ {username} ].',
            ['username' => $this->username]
        );
        Yii::$app->session->setFlash('success', $successText);

        if (Yii::$app->getModule('user')->enableActivation) {
            ///[Yii2 uesr:activation via email:signup]send activation email
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => '@yongtiger/user/mail/activationKey-html', 'text' => '@yongtiger/user/mail/activationKey-text'],
                    ['user' => $this->getUser()]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($this->email)
                ->setSubject(Module::t('user', 'Activation mail of the registration from ') . Yii::$app->name)
                ->send();

            Yii::$app->session->setFlash('warning', Module::t('user', 'Please check your email to activate your account.'));
        }

        $this->trigger(self::EVENT_AFTER_SIGNUP, new ModelEvent());
    }
}
