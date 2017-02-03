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
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;
use yongtiger\user\models\User;
use yongtiger\user\models\LoginForm;
use yongtiger\user\models\SignupForm;
use yongtiger\user\models\Oauth;
use yongtiger\user\traits\OauthTrait;

/**
 * Security Controller
 *
 * @package yongtiger\user\controllers
 */
class SecurityController extends Controller
{
    use OauthTrait;

    /**
     * @inheritdoc
     */
    public $defaultAction = 'login';

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
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableLoginWithCaptcha) {
            $behaviors['access']['rules'][0]['actions'] = array_merge($behaviors['access']['rules'][0]['actions'], ['captcha']);
        }

        ///[Yii2 uesr:oauth]
        if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)) {
            $behaviors['access']['rules'][0]['actions'] = array_merge($behaviors['access']['rules'][0]['actions'], ['auth']);
            $behaviors['access']['rules'][1]['actions'] = array_merge($behaviors['access']['rules'][1]['actions'], ['connect', 'disconnect', 'auth']);
        }

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions =[];

        if (Yii::$app->getModule('user')->enableLogin && (Yii::$app->getModule('user')->enableLoginWithUsername || Yii::$app->getModule('user')->enableLoginWithEmail || Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false))) {

            ///[Yii2 uesr:verifycode]
            if (Yii::$app->getModule('user')->enableLoginWithCaptcha) {
                $actions = array_merge($actions ,['captcha' => Yii::$app->getModule('user')->captcha]);
            }

            ///[Yii2 uesr:oauth]
            if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)) {
                $auth = [
                    'successCallback' => Yii::$app->user->isGuest ? [$this, 'authenticate'] : [$this, 'connect'],
                ];

                $actions = array_merge($actions ,['auth' => ArrayHelper::merge(Yii::$app->getModule('user')->auth, $auth)]);
            }
        }

        return $actions;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (Yii::$app->getModule('user')->enableLogin && (Yii::$app->getModule('user')->enableLoginWithUsername || Yii::$app->getModule('user')->enableLoginWithEmail || Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false))) {

            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }

            $model = new LoginForm();

            ///[Yii2 uesr:login with username or email]
            $post = Yii::$app->request->post();
            if (Yii::$app->getModule('user')->enableLoginWithUsername && Yii::$app->getModule('user')->enableLoginWithEmail && !empty($post[$model->formName()]['usernameOrEmail'])) {
                //If we have a @ in the username, then it should be an email
                if(strpos($post[$model->formName()]['usernameOrEmail'], '@') === false){
                    $post[$model->formName()]['username'] = $post[$model->formName()]['usernameOrEmail'];
                    Yii::$app->getModule('user')->enableLoginWithEmail = false;
                } else {
                    $post[$model->formName()]['email'] = $post[$model->formName()]['usernameOrEmail'];
                    Yii::$app->getModule('user')->enableLoginWithUsername = false;
                }
            }

            $load = $model->load($post);

            ///[Yii2 uesr:Ajax validation]
            if (Yii::$app->getModule('user')->enableLoginAjaxValidation && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($load && $model->login()) {
                return $this->goBack();
            } else {
                return $this->render('login', ['model' => $model]);
            }
        } else {
            Yii::$app->session->addFlash('info', Yii::$app->getModule('user')->disableLoginMessage);
            return $this->goHome();
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    ///[Yii2 uesr:oauth]
    /**
     * Authenticates user via social network.
     *
     * If user has already bound with the network's account, will be logged in. Otherwise, it will try to signup.
     *
     * @param \yongtiger\authclient\clients\IAuth $client
     */
    public function authenticate(\yongtiger\authclient\clients\IAuth $client)
    {
        $user = User::findByOauth($client->provider, $client->openid);

        if ($user) {

            ///Updates user's oauth info.
            $user->oauths[0]->updateAttributes($client->getUserInfos());

        } else {

            ///[Yii2 uesr:oauth signup]
            if (Yii::$app->getModule('user')->enableSignup && Yii::$app->getModule('user')->enableOauthSignup) {

                $model = new SignupForm(['scenario' => SignupForm::SCENARIO_OAUTH]);

                if (Yii::$app->getModule('user')->enableOauthSignupValidation && Yii::$app->getModule('user')->enableSignupWithUsername) {
                    $model->username = $client->fullname;
                }
                if (Yii::$app->getModule('user')->enableOauthSignupValidation && Yii::$app->getModule('user')->enableSignupWithEmail) {
                    $model->email = $client->email;
                }

                if ($user = $model->signup(Yii::$app->getModule('user')->enableOauthSignupValidation)) {
                    ///Insert a new record to the oauth ActiveRecord.
                    $this->insertOauth($user->id, $client->getUserInfos());
                } else {
                    ///Sets oauth session and passes to the signup page.
                    $this->setOauthSession($model, $client->getUserInfos());
                    return;
                }

            } else {
                Yii::$app->session->addFlash('error', Module::t('user', 'Login failed! A user bound to this oAuth client was not found.'));
                $this->action->successUrl = $this->action->cancelUrl;
                return;
            }

        }

        ///Logs in a user.
        if (!(new LoginForm(['user' => $user]))->login(false)) {
            $this->action->successUrl = $this->action->cancelUrl;
        }

    }

    ///[Yii2 uesr:account oauth]
    /**
     * Connects user via social network.
     *
     * @param \yongtiger\authclient\clients\IAuth $client
     */
    public function connect(\yongtiger\authclient\clients\IAuth $client)
    {
        $oauth = Oauth::findOne(['user_id' => Yii::$app->user->identity->id, 'provider' => $client->provider]);

        if ($oauth) {
            Yii::$app->session->addFlash('warning', Module::t('user', 'Already connected. No need to connect again.'));
        } else {
            ///Insert a new record to the oauth ActiveRecord.
            try {
                $this->insertOauth(Yii::$app->user->identity->id, $client->getUserInfos());
            } catch (Exception $e) {
                Yii::$app->session->addFlash('error', Module::t('user', 'Failed connect!'));
            }
        }
    }

    ///[Yii2 uesr:account oauth]
    /**
     * Disconnects user via social network.
     *
     * @param string $provider
     */
    public function actionDisconnect($provider)
    {
        $oauth = Oauth::findOne(['user_id' => Yii::$app->user->identity->id, 'provider' => $provider]);

        if ($oauth && $oauth->delete() !== 'false') {
            Yii::$app->session->addFlash('success', Module::t('user', 'Successfully disconnect.'));
        } else {
            Yii::$app->session->addFlash('error', Module::t('user', 'Failed disconnect!'));
        }

        return $this->redirect(['account/index']);
    }
}
