<?php ///[Yii2 uesr:token]

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
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

/**
 * Send Token Form Model
 *
 * @package yongtiger\user\models
 * @property string $email
 * @property string $verifyCode
 * @property \yongtiger\user\models\User $user read-only user
 */
class SendTokenForm extends Model
{
    const SCENARIO_ACTIVATION = 'activation';
    const SCENARIO_RECOVERY = 'recovery';
    const SCENARIO_VERIFICATION = 'verification';

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
    public function init()
    {
        parent::init();
        ///[Yii2 uesr:token]SendTokenWithoutLoad
        if (Yii::$app->getModule('user')->enableSendTokenWithoutLoad && !Yii::$app->user->isGuest) {
            $this->_user = Yii::$app->user->identity;
            $this->email = Yii::$app->user->identity->email;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_ACTIVATION] = $scenarios[static::SCENARIO_RECOVERY] = $scenarios[static::SCENARIO_VERIFICATION] = $scenarios[static::SCENARIO_DEFAULT];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'exist',  ///@see http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#exist
                'skipOnError' => true,
                'targetClass' => User::className(),
                'filter' => ['status' => $this->scenario == static::SCENARIO_ACTIVATION ? User::STATUS_INACTIVE : User::STATUS_ACTIVE],
                'message' => Module::t('user', 'There is no user with such email.')
            ],
        ];

        if (Yii::$app->getModule('user')->enableSendTokenWithCaptcha) {
            $rules = array_merge($rules, [
                ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
                ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
                ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/token/captcha'],
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

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableSendTokenWithCaptcha) {
            $attributeLabels['verifyCode'] = Module::t('user', 'Verification Code');
        }

        return $attributeLabels;
    }

    /**
     * Finds user by username or email.
     *
     * @return User|null User object or null
     */
    public function getUser()
    {
        if ($this->_user === null) {

            $this->_user = User::findOne([
                'email' => $this->email,
                'status' => $this->scenario === static::SCENARIO_ACTIVATION ? User::STATUS_INACTIVE : User::STATUS_ACTIVE,
            ]);
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
     * Sends email according to token type.
     *
     * @param bool $runValidation whether to perform validation (calling [[validate()]])
     * @return bool|null true if sent successfully, null if invalid scenario
     */
    public function send($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if ($this->getUser()) {
            ///Note: If duration is `0` (i.e. 1486065032_1486065032_hak0yANzhQAUVZoqWp-btm1dU2jN_ycx), `$validDuration > 0` return false.
            if ($this->getUser()->token && ($validDuration = TokenHandler::getValidDuration($this->getUser()->token)) > 0) {
                Yii::$app->session->addFlash('error', Module::t('user', 'Please do not send email repeatedly! Try again in {valid-duration}.', ['valid-duration' => Yii::$app->formatter->asDuration($validDuration)]));
                return false;
            } else {

                switch ($this->scenario) {
                    case static::SCENARIO_ACTIVATION:
                        $this->getUser()->status = User::STATUS_INACTIVE;
                        $this->getUser()->token = TokenHandler::generateExpiringRandomKey(Yii::$app->getModule('user')->signupWithEmailActivationExpire);
                        break;
                    case static::SCENARIO_RECOVERY:
                        $this->getUser()->token = TokenHandler::generateExpiringRandomKey(Yii::$app->getModule('user')->recoveryPasswordExpire);
                        break;
                    case static::SCENARIO_VERIFICATION:
                        $this->getUser()->token = TokenHandler::generateExpiringRandomKey(Yii::$app->getModule('user')->accountVerificatonExpire);
                        break;
                    default:
                        return null;
                }

                if ($this->getUser()->save(false)) {
                    return $this->sendEmail();
                }
                return false;
            }
        }

        Yii::$app->session->addFlash('error', Module::t('user', 'Failed to find a user!'));
        return false;
    }

    /**
     * Sends an email by `$this->scenario`.
     *
     * @return bool|null whether the email was send, null if invalid scenario
     */
    public function sendEmail()
    {
        switch ($this->scenario) {
            case static::SCENARIO_ACTIVATION:
                if (
                    Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => Yii::$app->getModule('user')->signupWithEmailActivationComposeHtml, 'text' => Yii::$app->getModule('user')->signupWithEmailActivationComposeText],
                        ['user' => $this->getUser()]
                    )
                    ->setFrom(Yii::$app->getModule('user')->signupWithEmailActivationSetFrom)
                    ->setTo($this->getUser()->email)
                    ->setSubject(Module::t('user', 'Activation mail of the registration from ') . Yii::$app->name)
                    ->send()
                ) {
                    Yii::$app->session->addFlash('success', Module::t('user', 'Please check your email [{youremail}] to activate your account.', ['youremail' => $this->getUser()->email]));
                    return true;
                }
                return false;

            case static::SCENARIO_RECOVERY:
                if (
                    Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => Yii::$app->getModule('user')->recoveryPasswordComposeHtml, 'text' => Yii::$app->getModule('user')->recoveryPasswordComposeText],
                        ['user' => $this->getUser()]
                    )
                    ->setFrom(Yii::$app->getModule('user')->recoveryPasswordSetFrom)
                    ->setTo($this->getUser()->email)
                    ->setSubject(Module::t('user', 'Password reset for ') . Yii::$app->name)
                    ->send()
                ) {
                    Yii::$app->session->addFlash('success', Module::t('user', 'Please check your email [{youremail}] for further instructions.', ['youremail' => $this->getUser()->email]));
                    return true;
                }
                return false;

            case static::SCENARIO_VERIFICATION:
                if (
                    Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => Yii::$app->getModule('user')->accountVerifyEmailComposeHtml, 'text' => Yii::$app->getModule('user')->accountVerifyEmailComposeText],
                        ['user' => $this->getUser()]
                    )
                    ->setFrom(Yii::$app->getModule('user')->accountVerifyEmailSetFrom)
                    ->setTo($this->getUser()->email)
                    ->setSubject(Module::t('user', 'Verification mail from ') . Yii::$app->name)
                    ->send()
                ) {
                    Yii::$app->session->addFlash('success', Module::t('user', 'Please check your email [{youremail}] to verify your email.', ['youremail' => $this->getUser()->email]));
                    return true;
                }
                return false;

            default:
                return null;
        }
    }
}