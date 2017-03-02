<?php ///[Yii2 uesr:account]

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
use yongtiger\user\Module;

/**
 * Change Form Model
 *
 * @package yongtiger\user\models
 * @property string $password
 * @property string $verifyCode
 * @property \yongtiger\user\models\User $user read-only user
 */
class ChangeForm extends Model
{
    const EVENT_BEFORE_CHANGE = 'beforeChange';
    const EVENT_AFTER_CHANGE = 'afterChange';

    /**
     * @var string password
     */
    public $password;

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
        $rules =  [];

        ///[Yii2 uesr:verify]
        if (isset(Yii::$app->user->identity->verify->password_verified_at)) {
            $rules = array_merge($rules, [
                // password is required
                ['password', 'required'],
                // password is validated by validatePassword()
                ['password', 'validatePassword'],
            ]);
        }

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableAccountChangeWithCaptcha) {
            $rules = array_merge($rules, [
                ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
                ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
                ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/account/captcha'],
            ]);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = [];

        ///[Yii2 uesr:verify]
        if (isset(Yii::$app->user->identity->verify->password_verified_at)) {
            $attributeLabels['password'] = Module::t('message', 'Password');
        }

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableAccountChangeWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('message', 'Verification Code');
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
                $this->addError($attribute, Module::t('message', 'Incorrect password.'));
            }
        }
    }

    /**
     * Gets user.
     *
     * @return User|null User object or null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    /**
     * This method is called before changing in a user.
     *
     * The default implementation will trigger the [[EVENT_BEFORE_CHANGE]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * You can get the user identity by $this->getUser() in your overrided method.
     *
     * ```php
     * public function beforeChange()
     * {
     *     if (parent::beforeChange()) {
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
     * @return bool whether the user should continue to be changed
     */
    protected function beforeChange()
    {
        $event = new ModelEvent();
        $this->trigger(static::EVENT_BEFORE_CHANGE, $event);

        if ($event->isValid) {

            // ...custom code here...

        }
        return $event->isValid;
    }

    /**
     * This method is called after the user is successfully changed.
     *
     * The default implementation will trigger the [[EVENT_AFTER_CHANGE]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * You can get the user identity by $this->getUser() in your overrided method.
     *
     * ```php
     * public function afterChange()
     * {
     *     // ...custom code here...
     *     $this->getUser();
     *
     *     parent::afterChange();
     * }
     * ```
     *
     */
    protected function afterChange()
    {
        // ...custom code here...
        Yii::$app->session->addFlash('success', Module::t('message', 'Successfully changed.'));

        $this->trigger(static::EVENT_AFTER_CHANGE, new ModelEvent());
    }
}