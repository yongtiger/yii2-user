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
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;
use yongtiger\user\models\User;
use yongtiger\user\models\SignupForm;
use yongtiger\user\models\SendTokenForm;
use yongtiger\user\models\Oauth;
use yongtiger\user\traits\OauthTrait;

/**
 * Registration Controller
 *
 * @package yongtiger\user\controllers
 */
class RegistrationController extends Controller
{
    use OauthTrait;

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
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableSignupWithCaptcha) {
            $behaviors['access']['rules'][0]['actions'] = array_merge($behaviors['access']['rules'][0]['actions'], ['captcha']);
        }

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
                $actions = array_merge($actions ,['captcha' => Yii::$app->getModule('user')->captcha]);
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
            $model = new SignupForm($this->getOauthSession()['signup-form']);

            $load = $model->load(Yii::$app->request->post());

            ///[Yii2 uesr:Ajax validation]
            if (Yii::$app->getModule('user')->enableSignupAjaxValidation && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($load && $user = $model->signup()) {

                ///[Yii2 uesr:oauth signup]
                if (Yii::$app->getModule('user')->enableOauthSignup && Yii::$app->getModule('user')->enableOauthSignupValidation) {
                    if ($client = $this->getOauthSession()['auth-client']) {
                        $this->insertOauth($user->id, $client); ///Insert a new record to the oauth ActiveRecord.
                        $this->clearOauthSession();
                    }
                }

                if (!(Yii::$app->getModule('user')->enableSignupWithEmail && Yii::$app->getModule('user')->enableSignupWithEmailActivation)) {
                    Yii::$app->user->login($user);
                    return $this->redirect(['account/index']);
                }

                return $this->goHome();
            }

            return $this->render('signup', ['model' => $model]);

        } else {
            ///[v0.9.5 (fix:backend disableSignupMessage)]
            if (Yii::$app->getModule('user')->disableSignupMessage) {
                Yii::$app->session->addFlash('info', Yii::$app->getModule('user')->disableSignupMessage);
            }
            return $this->goHome();
        }
    }
}
