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

namespace yongtiger\user\controllers;

use Yii;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yongtiger\user\models\SignupForm;
use yongtiger\user\Module;
use yongtiger\user\models\User;
use yongtiger\user\models\ActivationForm;
use yongtiger\user\models\ResendForm;

/**
 * Registration Controller
 *
 * @package yongtiger\user\controllers
 */
class RegistrationController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'signup';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['signup','activate', 'resend'],
                'rules' => [
                    [
                        'actions' => ['signup', 'activate', 'resend'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions =[];

        if (Yii::$app->getModule('user')->enableSignup && (Yii::$app->getModule('user')->enableSignupWithUsername || Yii::$app->getModule('user')->enableSignupWithEmail)) {

            ///[Yii2 uesr:verifycode]
            if (Yii::$app->getModule('user')->enableSignupWithCaptcha) {
                $actions = array_merge($actions, ['captcha' => Yii::$app->getModule('user')->captcha]);
            }

        }

        return $actions;
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (Yii::$app->getModule('user')->enableSignup && (Yii::$app->getModule('user')->enableSignupWithEmail || Yii::$app->getModule('user')->enableSignupWithUsername)) {

            ///[Yii2 uesr:oauth signup]
            $model = Yii::$app->session['signup-form'] ? : new SignupForm();
            unset(Yii::$app->session['signup-form']);

            $load = $model->load(Yii::$app->request->post());

            ///[Yii2 uesr:Ajax validation]
            if (Yii::$app->getModule('user')->enableSignupAjaxValidation) {
                ///Note: Should be handled as soon as possible ajax!
                ///Note: CAPTCHA validation should not be used in AJAX validation mode.
                ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
            }

            if ($load && $user = $model->signup()) {

                ///[Yii2 uesr:oauth signup]
                if (Yii::$app->getModule('user')->enableOauthSignup && Yii::$app->getModule('user')->enableOauthSignupValidation) {
                    if ($client = Yii::$app->session['auth-client']) {
                        unset(Yii::$app->session['auth-client']); 

                        ///Add a new record to the oauth database table.
                        $auth = new \yongtiger\user\models\Oauth(['user_id' => $user->id]);
                        $auth->attributes = $client;   ///Massive Assignment @see http://www.yiiframework.com/doc-2.0/guide-structure-models.html#massive-assignment
                        $auth->save(false);
                    }
                }

                if (!(Yii::$app->getModule('user')->enableSignupWithEmail && Yii::$app->getModule('user')->enableSignupWithEmailActivation)) {
                    Yii::$app->user->login($user);
                }
                return $this->goHome();
            }

            return $this->render('signup', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->session->addFlash('info', Yii::$app->getModule('user')->disableSignupMessage);
            return $this->goHome();
        }
    }

    ///[Yii2 uesr:activation via email:activate]
    /**
     * Activate a new user.
     *
     * @param string $key Activation key.
     */
    public function actionActivate($key)
    {
        $model = new ActivationForm(['activation_key' => $key]);

        if ($user = $model->activate()) {
            Yii::$app->user->login($user);
        }

        return $this->goHome();
    }

    ///[Yii2 uesr:activation via email:resend]
    /**
     * Resend email activation key.
     */
    public function actionResend()
    {
        $model = new ResendForm();

        $load = $model->load(Yii::$app->request->post());

        ///[Yii2 uesr:Ajax validation]
        if (Yii::$app->getModule('user')->enableSignupAjaxValidation) {
            ///Note: Should be handled as soon as possible ajax!
            ///Note: CAPTCHA validation should not be used in AJAX validation mode.
            ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        if ($load && $model->resend()) {
            return $this->goHome();
        }

        return $this->render(
            'resend',
            [
                'model' => $model
            ]
        );
    }
}
