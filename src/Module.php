<?php ///[Yii2 user]

/**
 * Yii2 user
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yongtiger\user\models\User;

/**
 * Class Module
 *
 * @package yongtiger\user
 */
class Module extends \yii\base\Module
{
    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     *
     * @see http://www.yiiframework.com/doc-2.0/yii-base-module.html#$defaultRoute-detail
     */
    public $defaultRoute = 'account';

    /**
     * @var string The controller namespace to use
     */
    public $controllerNamespace = 'yongtiger\user\controllers';

    ///Signup

    /**
     * @var bool Enable signup
     */
    public $enableSignup = true;

    /**
     * @var string The meesage to display while `$enableSignup` is `false`
     */
    public $disableSignupMessage;    ///init later

    /**
     * @var bool Enable signup with username
     * You have to enable `$enableSignupWithUsername` and/or `$enableSignupWithEmail` at least one.
     * If neither, effect is equivalent to `$enableSignup` being `false`.
     */
    public $enableSignupWithUsername = true;

    /**
     * @var bool Enable signup with re-password
     */
    public $enableSignupWithRepassword = true;

    /**
     * @var bool Enable signup with email
     * You have to enable `$enableSignupWithUsername` and/or `$enableSignupWithEmail` at least one.
     * If neither, effect is equivalent to `$enableSignup` being `false`.
     */
    public $enableSignupWithEmail = true;

    /**
     * @var bool Enable activation while signup with email
     * It will be invalid when `$enableSignupWithEmail` is `false`.
     */
    public $enableSignupWithEmailActivation = true;

    /**
     * @var int The time before an activation key becomes invalid
     * It will be invalid when either `$enableSignupWithEmail` or `$enableSignupWithEmailActivation` is `false`.
     */
    public $signupWithEmailActivationExpire = 600; // 10 minutess, if `0` means never expired.

    /**
     * @var string Html email body file of activation while signup with email
     */
    public $signupWithEmailActivationComposeHtml = '@yongtiger/user/mail/activate-status-html';

    /**
     * @var string Text email body file of activation while signup with email
     */
    public $signupWithEmailActivationComposeText = '@yongtiger/user/mail/activate-status-text';

    /**
     * @var string|array Sender email address of activation while signup with email
     */
    public $signupWithEmailActivationSetFrom; ///init later

    /**
     * @var bool Enable signup `AjaxValidation`
     */
    public $enableSignupAjaxValidation = true;

    /**
     * @var bool Enable signup `ClientValidation`
     */
    public $enableSignupClientValidation = true;

    /**
     * @var bool Enable signup `ValidateOnBlur`
     */
    public $enableSignupValidateOnBlur = true;

    /**
     * @var bool Enable signup `ValidateOnSubmit`
     */
    public $enableSignupValidateOnSubmit = true;

    /**
     * @var bool Enable signup with `Captcha`
     */
    public $enableSignupWithCaptcha = true;

    ///Login

    /**
     * @var bool Enable login
     */
    public $enableLogin = true;

    /**
     * @var bool The meesage to display while `$enableLogin` is `false`
     */
    public $disableLoginMessage;    ///init later

    /**
     * @var bool Enable login with username
     * In order to login, you have to enable `$enableLoginWithUsername` and/or `$enableSignupWithEmail` and/or `$enableOauth` at least one.
     * If neither, effect is equivalent to `$enableLogin` being `false`.
     */
    public $enableLoginWithUsername = true; ///UsernamePassword and/or EmailPassword and/or Oauth

    /**
     * @var bool Enable login with email
     * In order to login, you have to enable `$enableLoginWithUsername` and/or `$enableSignupWithEmail` and/or `$enableOauth` at least one.
     * If neither, effect is equivalent to `$enableLogin` being `false`.
     */
    public $enableLoginWithEmail = true;

    /**
     * @var bool Enable login `AjaxValidation`
     */
    public $enableLoginAjaxValidation = true;

    /**
     * @var bool Enable login `ClientValidation`
     */
    public $enableLoginClientValidation = true;

    /**
     * @var bool Enable login `ValidateOnBlur`
     */
    public $enableLoginValidateOnBlur = true;

    /**
     * @var bool Enable login `ValidateOnSubmit`
     */
    public $enableLoginValidateOnSubmit = true;

    /**
     * @var bool Enable login with `Captcha`
     */
    public $enableLoginWithCaptcha = true;

    ///[Yii2 uesr:recovery]

    /**
     * @var bool Enable recovery password
     */
    public $enableRecoveryPassword = true;  ///[v0.9.7 (backend:enableRecoveryPassword)]

    /**
     * @var int The time before a recovery password key becomes invalid
     */
    public $recoveryPasswordExpire = 600; // 10 minutess, if `0` means never expired.

    /**
     * @var string Html email body file of recovery password
     */
    public $recoveryPasswordComposeHtml = '@yongtiger/user/mail/recover-password-html';

    /**
     * @var string Text email body file of recovery password
     */
    public $recoveryPasswordComposeText = '@yongtiger/user/mail/recover-password-text';

    /**
     * @var string|array Sender email address of recovery password
     */
    public $recoveryPasswordSetFrom; ///init later

    ///[Yii2 uesr:account]

    /**
     * @var bool Enable account
     */
    public $enableAccount = true;

    /**
     * @var bool Enable account changing with password
     */
    public $enableAccountChangeWithPassword = true;

    /**
     * @var bool Enable account changing password with re-password
     */
    public $enableAccountChangePasswordWithRepassword = true;

    /**
     * @var int The time before an activation key becomes invalid
     * It will be invalid when either `$enableSignupWithEmail` or `$enableSignupWithEmailActivation` is `false`.
     */
    public $accountVerificatonExpire = 600; // 10 minutess, if `0` means never expired.

    /**
     * @var string Html email body file of account verification email
     */
    public $accountVerifyEmailComposeHtml = '@yongtiger/user/mail/verify-email-html';

    /**
     * @var string Text email body file of account verification email
     */
    public $accountVerifyEmailComposeText = '@yongtiger/user/mail/verify-email-text';

    /**
     * @var string|array Sender email address of account verification email
     */
    public $accountVerifyEmailSetFrom; ///init later

    /**
     * @var bool Enable account changing `AjaxValidation`
     */
    public $enableAccountChangeAjaxValidation = true;

    /**
     * @var bool Enable account changing `ClientValidation`
     */
    public $enableAccountChangeClientValidation = true;

    /**
     * @var bool Enable account changing `ValidateOnBlur`
     */
    public $enableAccountChangeValidateOnBlur = true;

    /**
     * @var bool Enable account changing `ValidateOnSubmit`
     */
    public $enableAccountChangeValidateOnSubmit = true;

    /**
     * @var bool Enable account changing with `Captcha`
     */
    public $enableAccountChangeWithCaptcha = true;

    ///[Yii2 uesr:token]
    /**
     * @var bool Enable send token
     */
    public $enableSendToken = true;

    /**
     * @var bool Enable send token without load (only for logged-in user!)
     */
    public $enableSendTokenWithoutLoad = true;

    /**
     * @var bool Enable token `AjaxValidation`
     */
    public $enableSendTokenAjaxValidation = true;

    /**
     * @var bool Enable token `ClientValidation`
     */
    public $enableSendTokenClientValidation = true;

    /**
     * @var bool Enable token `ValidateOnBlur`
     */
    public $enableSendTokenValidateOnBlur = true;

    /**
     * @var bool Enable token `ValidateOnSubmit`
     */
    public $enableSendTokenValidateOnSubmit = true;
    
    /**
     * Note: Should be handled as soon as possible ajax!
     * Note: CAPTCHA validation should not be used in AJAX validation mode.
     * @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
     *
     * @var bool Enable token with `Captcha`
     */
    public $enableSendTokenWithCaptcha = true;

    ///[Yii2 uesr:captcha]

    /**
     * @var array
     *
     * ```
     * [
     *     'class' => 'yii\captcha\CaptchaAction',
     *     'controller'=>'login',  ///The controller that owns this action
     *     'backColor'=>0xFFFFFF,  ///The background color. For example, 0x55FF00. Defaults to 0xFFFFFF, meaning white color.
     *     'foreColor'=>0x2040A0,  ///The font color. For example, 0x55FF00. Defaults to 0x2040A0 (blue color).
     *     'padding' => 5,         ///Padding around the text. Defaults to 2.
     *     'offset'=>-2,           ///The offset between characters. Defaults to -2. You can adjust this property in order to decrease or increase the readability of the captcha.
     *     'height' => 36,         ///The height of the generated CAPTCHA image. Defaults to 50. need to be adjusted according to the specific verification code bit
     *     'width' => 96,          ///The width of the generated CAPTCHA image. Defaults to 120.
     *     'maxLength' =>6,        ///The maximum length for randomly generated word. Defaults to 7.
     *     'minLength' =>4,        ///The minimum length for randomly generated word. Defaults to 6.
     *     'testLimit'=>5,         ///How many times should the same CAPTCHA be displayed. Defaults to 3. A value less than or equal to 0 means the test is unlimited (available since version 1.1.2). Note that when 'enableClientValidation' is true (default), it will be invalid!
     *     'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,    ///The fixed verification code. When this property is set, getVerifyCode() will always return the value of this property. This is mainly used in automated tests where we want to be able to reproduce the same verification code each time we run the tests. If not set, it means the verification code will be randomly generated.
     * ]
     * ```
     */
    public $captcha = [];   ///init later

    /**
     * @var array
     *
     * ```
     * [
     *     'class' => 'yii\captcha\Captcha',
     *     'imageOptions' => ['alt' => 'Verification Code', 'title' => 'Click to change another verification code.'],
     *     'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
     * ]
     ** ```
     */
    public $captchaActiveFieldWidget = [];    ///init later

    ///[Yii2 uesr:oauth]

    /**
     * @var bool Enable oauth
     * In order to login, you have to enable `$enableLoginWithUsername` and/or `$enableSignupWithEmail` and/or `$enableOauth` at least one.
     * If neither, effect is equivalent to `$enableLogin` being `false`.
     */
    public $enableOauth = true;

    /**
     * @var bool Enable oauth singup when cannot find a user bond with oauth
     * It will be invalid when `$enableOauth` is `false`.
     */
    public $enableOauthSignup = true;

    /**
     * @var bool Enable oauth singup validation, redirect back to signup page
     * It will be invalid when `$enableOauth` or `$enableOauthSignup` is `false`.
     */
    public $enableOauthSignupValidation = true;

    /**
     * @var array
     *
     * ```
     * [
     *     'baseAuthUrl' => new \yii\helpers\ReplaceArrayValue(['security/auth']),  ///cannot be `['security/auth']`! ArrayHelper::merge will get wrong result. instead, we use `ReplaceArrayValue`.
     *     'popupMode' => false,     ///defaults to true
     *     'options' => ['class'=>'control-label'], ///widget div options
     *     'clientOptions' => [
     *         'popup'=> [
     *             'resizable'=>'yes',
     *             'scrollbars'=>'yes',
     *             'toolbar'=>'no',
     *             'menubar'=>'no',
     *             'location'=>'no',
     *             'directories'=>'no',
     *             'status'=>'yes',
     *             'width'=>450,
     *             'height'=>380,
     *         ]
     *     ],
     * ]
     * ```
     */
    public $authChoiceWidgetConfig =[];   ///init later

    /**
     * @var array
     *
     * ```
     * [
     *     'class' => 'yii\authclient\AuthAction',
     *     // 'successCallback' => Yii::$app->user->isGuest ? [$this, 'authenticate'] : [$this, 'connect'],   ///cannot configure 'successCallback' here because of `$this`!!!
     *     ///Cannot use `Yii::$app` here! we will use `Yii::$app->urlManager->createUrl()` in module init() later
     *     ///Cannot be `['security/auth']`! `ArrayHelper::merge` will get wrong result. instead, we use `ReplaceArrayValue`.
     *     'successUrl' => new \yii\helpers\ReplaceArrayValue(['user/account/index']),
     *     'cancelUrl' => new \yii\helpers\ReplaceArrayValue(['user/security/login']),
     * ]
     * ```
     */
    public $auth = [];  ///init later

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!($this->enableSignup && ($this->enableSignupWithEmail || $this->enableSignupWithUsername))) {
            ///[v0.9.5 (fix:backend disableSignupMessage)]
            if ($this->disableSignupMessage === null) {
                $this->disableSignupMessage = Module::t('message', 'This site has been closed registration.');
            }
        }

        if (!($this->enableLogin && ($this->enableLoginWithUsername || $this->enableLoginWithEmail || $this->enableOauth && Yii::$app->get("authClientCollection", false)))) {
            ///[v0.9.6 (fix:backend disableLoginMessage)]
            if ($this->disableLoginMessage === null) {
                $this->disableLoginMessage = Module::t('message', 'This site has been closed login.');
            }
        }

        if ($this->enableSignup && !isset($this->signupWithEmailActivationSetFrom)) {
            $this->signupWithEmailActivationSetFrom = [Yii::$app->params['serviceEmail'] => Yii::$app->name . ' robot'];
        }

        if ($this->enableRecoveryPassword && !isset($this->recoveryPasswordSetFrom)) {
            $this->recoveryPasswordSetFrom = [Yii::$app->params['serviceEmail'] => Yii::$app->name . ' robot'];
        }

        if ($this->enableAccount && !isset($this->accountVerifyEmailSetFrom)) {
            $this->accountVerifyEmailSetFrom = [Yii::$app->params['serviceEmail'] => Yii::$app->name . ' robot'];
        }

        if ($this->enableSignup && $this->enableSignupWithCaptcha || $this->enableLogin && $this->enableLoginWithCaptcha || $this->enableSendToken && $this->enableSendTokenWithCaptcha) {
            $this->captcha = ArrayHelper::merge([
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 36,         ///The height of the generated CAPTCHA image. Defaults to 50. need to be adjusted according to the specific verification code bit
                'width' => 96,          ///The width of the generated CAPTCHA image. Defaults to 120.
                'maxLength' =>6,        ///The maximum length for randomly generated word. Defaults to 7.
                'minLength' =>4,        ///The minimum length for randomly generated word. Defaults to 6.
                'testLimit'=>5,         ///How many times should the same CAPTCHA be displayed. Defaults to 3. A value less than or equal to 0 means the test is
            ], $this->captcha);

            $this->captchaActiveFieldWidget = ArrayHelper::merge([
                'class' => 'yii\captcha\Captcha',
                'imageOptions' => ['alt' => Module::t('message', 'Verification Code'), 'title' => Module::t('message', 'Click to change another verification code.')],
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ], $this->captchaActiveFieldWidget);
        }

        if ($this->enableOauth) {
            $this->authChoiceWidgetConfig = ArrayHelper::merge([
                'baseAuthUrl' => ['security/auth'],
            ], $this->authChoiceWidgetConfig);

            $this->auth = ArrayHelper::merge([
                'class' => 'yii\authclient\AuthAction',
                'successUrl' => ['user/account/index'],
                'cancelUrl' => ['user/account/index'],
            ], $this->auth);
            $this->auth['successUrl'] = Yii::$app->urlManager->createUrl($this->auth['successUrl']);
            $this->auth['cancelUrl'] = Yii::$app->urlManager->createUrl($this->auth['cancelUrl']);

        }
    }

    ///[v0.16.1 (i18n:public static function registerTranslation)]
    /**
     * Registers the translation files.
     */
    public static function registerTranslations()
    {
        ///[i18n]
        ///if no setup the component i18n, use setup in this module.
        if (!isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-user/*']) && !isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-user'])) {
            Yii::$app->i18n->translations['extensions/yongtiger/yii2-user/*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@vendor/yongtiger/yii2-user/src/messages',    ///default base path is '@vendor/yongtiger/yii2-user/src/messages'
                'fileMap' => [
                    'extensions/yongtiger/yii2-user/message' => 'message.php',  ///category in Module::t() is message
                ],
            ];
        }
    }

    /**
     * Translates a message. This is just a wrapper of Yii::t().
     *
     * @see http://www.yiiframework.com/doc-2.0/yii-baseyii.html#t()-detail
     *
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        static::registerTranslations(); ///[v0.16.1 (i18n:public static function registerTranslation)]
        return Yii::t('extensions/yongtiger/yii2-user/' . $category, $message, $params, $language);
    }
}
