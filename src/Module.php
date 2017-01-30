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
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
    public $defaultRoute = 'security';

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
     * @var bool Enable signup with email
     * You have to enable `$enableSignupWithUsername` and/or `$enableSignupWithEmail` at least one.
     * If neither, effect is equivalent to `$enableSignup` being `false`.
     */
    public $enableSignupWithEmail = true;

    /**
     * @var bool Enable activation while signup with email
     * It will be invalid when `$enableSignupWithEmail` is `false`.
     * Note: It does not affect `activate` and `resend`, that means user still can resend an activation email or activate account!
     */
    public $enableSignupWithEmailActivation = true;

    /**
     * @var int The time before an activation key becomes invalid
     * It will be invalid when either `$enableSignupWithEmail` or `$enableSignupWithEmailActivation` is `false`.
     */
    public $signupWithEmailActivationExpire = 86400; // 24 hours, if `0` means never expired.

    /**
     * @var string Html email body file of activation while signup with username
     */
    public $signupWithEmailActivationComposeHtml = '@yongtiger/user/mail/activationKey-html';

    /**
     * @var string Text email body file of activation while signup with username
     */
    public $signupWithEmailActivationComposeText = '@yongtiger/user/mail/activationKey-text';

    /**
     * @var string|array Sender email address of activation while signup with username
     */
    public $signupWithEmailActivationSetFrom; ///init later

    /**
     * @var bool Enable signup with re-password
     */
    public $enableSignupWithRepassword = true;

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
     * @var bool Enable signup with Captcha
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
     * You have to enable `$enableLoginWithUsername` and/or `$enableSignupWithEmail` and/or `$enableOauth` at least one.
     * If neither, effect is equivalent to `$enableLogin` being `false`.
     */
    public $enableLoginWithUsername = true; ///UsernamePassword and/or EmailPassword and/or Oauth

    /**
     * @var bool Enable login with email
     * You have to enable `$enableLoginWithUsername` and/or `$enableSignupWithEmail` and/or `$enableOauth` at least one.
     * If neither, effect is equivalent to `$enableLogin` being `false`.
     */
    public $enableLoginWithEmail = false;

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
     * @var bool Enable login with Captcha
     */
    public $enableLoginWithCaptcha = true;

    ///RequestPasswordReset
    /**
     * @var bool Enable RequestPasswordReset `AjaxValidation`
     */
    public $enableRequestPasswordResetAjaxValidation = true;

    /**
     * @var bool Enable RequestPasswordReset `ClientValidation`
     */
    public $enableRequestPasswordResetClientValidation = true;

    /**
     * @var bool Enable RequestPasswordReset `ValidateOnBlur`
     */
    public $enableRequestPasswordResetValidateOnBlur = true;

    /**
     * @var bool Enable RequestPasswordReset `ValidateOnSubmit`
     */
    public $enableRequestPasswordResetValidateOnSubmit = true;

    /**
     * @var bool Enable RequestPasswordReset with Captcha
     */
    public $enableRequestPasswordResetWithCaptcha = true;

    /**
     * @var string Html email body file of RequestPasswordReset
     */
    public $requestPasswordResetComposeHtml = '@yongtiger/user/mail/passwordResetToken-html';

    /**
     * @var string Text email body file of RequestPasswordReset
     */
    public $requestPasswordResetComposeText = '@yongtiger/user/mail/passwordResetToken-text';

    /**
     * @var bool Enable RequestPasswordReset with email
     */
    public $requestPasswordResetSetFrom; ///init later

    ///Captcha
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

    ///Oauth
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
     * @var bool Enable account changing with password
     */
    public $enableAccountChangeWithPassword = true;

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
     * @var bool Enable account changing with Captcha
     */
    public $enableAccountChangeWithCaptcha = true;

    /**
     * @var bool Enable account changing password with repassword
     */
    public $enableAccountChangePasswordWithRepassword = true;

    /**
     * @var string Html email body file of RequestPasswordReset
     */
    public $accountVerificationEmailComposeHtml = '@yongtiger/user/mail/account-verification-email-html';

    /**
     * @var string Text email body file of RequestPasswordReset
     */
    public $accountVerificationEmailComposeText = '@yongtiger/user/mail/account-verification-email-text';

    /**
     * @var bool Enable RequestPasswordReset with email
     */
    public $accountVerificationEmailSetFrom; ///init later

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        if (!($this->enableSignup && ($this->enableSignupWithEmail || $this->enableSignupWithUsername))) {
            $this->disableSignupMessage = Module::t('user', 'This site has been closed registration.');
        }

        if (!($this->enableLogin && ($this->enableLoginWithUsername || $this->enableLoginWithEmail || $this->enableOauth && Yii::$app->get("authClientCollection", false)))) {
            $this->disableLoginMessage = Module::t('user', 'This site has been closed login.');
        }

        if (!isset($this->signupWithEmailActivationSetFrom)) {
            $this->signupWithEmailActivationSetFrom = [Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'];
        }

        if (!isset($this->requestPasswordResetSetFrom)) {
            $this->requestPasswordResetSetFrom = [Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'];
        }

        if (!isset($this->accountVerificationEmailSetFrom)) {
            $this->accountVerificationEmailSetFrom = [Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'];
        }

        if ($this->enableSignupWithCaptcha || $this->enableLoginWithCaptcha || $this->enableRequestPasswordResetWithCaptcha) {
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
                'imageOptions' => ['alt' => Module::t('user', 'Verification Code'), 'title' => Module::t('user', 'Click to change another verification code.')],
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
                'cancelUrl' => ['user/security/login'],
            ], $this->auth);
            $this->auth['successUrl'] = Yii::$app->urlManager->createUrl($this->auth['successUrl']);
            $this->auth['cancelUrl'] = Yii::$app->urlManager->createUrl($this->auth['cancelUrl']);

        }
    }

    /**
     * Registers the translation files.
     */
    protected function registerTranslations()
    {
        ///[i18n]
        ///if no setup the component i18n, use setup in this module.
        if (!isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-user/*']) && !isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-user'])) {
            Yii::$app->i18n->translations['extensions/yongtiger/yii2-user/*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@vendor/yongtiger/yii2-user/src/messages',    ///default base path is '@vendor/yongtiger/yii2-user/src/messages'
                'fileMap' => [
                    'extensions/yongtiger/yii2-user/user' => 'user.php',  ///category in Module::t() is user
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
        return Yii::t('extensions/yongtiger/yii2-user/' . $category, $message, $params, $language);
    }
}
