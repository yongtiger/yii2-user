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
use yongtiger\user\models\LoginForm;
use yongtiger\user\models\SignupForm;
use yongtiger\user\Module;
use yii\web\Response;
use yongtiger\user\models\User;

/**
 * Security Controller
 *
 * @package yongtiger\user\controllers
 */
class SecurityController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'login';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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
                $actions = array_merge($actions, ['captcha' => Yii::$app->getModule('user')->captcha]);
            }

            ///[Yii2 uesr:oauth]
            if (Yii::$app->getModule('user')->enableOauth) {
                $auth = [
                    'successCallback' => Yii::$app->user->isGuest ? [$this, 'authenticate'] : [$this, 'connect'],
                ];

                $actions = array_merge($actions, ['auth' => ArrayHelper::merge(Yii::$app->getModule('user')->auth, $auth)]);
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

            $load = $model->load(Yii::$app->request->post());

            ///[Yii2 uesr:Ajax validation]
            if (Yii::$app->getModule('user')->enableLoginAjaxValidation) {
                ///Note: Should be handled as soon as possible ajax!
                ///Note: CAPTCHA validation should not be used in AJAX validation mode.
                ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return \yii\widgets\ActiveForm::validate($model);
                }
            }

            if ($load && $model->login()) {
                return $this->goBack();
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
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
     * Authenticate user via social network.
     *
     * If user has already bound with the network's account, will be logged in. Otherwise, it will try to signup.
     *
     * @param \yongtiger\authclient\clients\IAuth $client
     */
    public function authenticate(\yongtiger\authclient\clients\IAuth $client)
    {
        ///Uncomment below to see which attributes you get back.
        ///First time to call `getUserAttributes()`, only return the basic attrabutes info for login, such as openid.
        // echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
        // echo "<pre>";print_r($client->provider);echo "</pre>";
        // echo "<pre>";print_r($client->openid);echo "</pre>";
        ///If `$attribute` is not exist in the basic user attrabutes, call `initUserInfoAttributes()` and merge the results into the basic user attrabutes.
        // echo "<pre>";print_r($client->email);echo "</pre>";
        ///After calling `initUserInfoAttributes()`, will return all user attrabutes.
        // echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
        // echo "<pre>";print_r($client->fullName);echo "</pre>";
        // echo "<pre>";print_r($client->firstName);echo "</pre>";
        // echo "<pre>";print_r($client->lastName);echo "</pre>";
        // echo "<pre>";print_r($client->language);echo "</pre>";
        // echo "<pre>";print_r($client->gender);echo "</pre>";
        // echo "<pre>";print_r($client->avatar);echo "</pre>";
        // echo "<pre>";print_r($client->link);echo "</pre>";
        ///Get all user infos at once.
        // echo "<pre>";print_r($client->getUserInfos());echo "</pre>";
        // exit;

        $user = User::findByOauth($client->provider, $client->openid);

        if (empty($user)) {

            ///[Yii2 uesr:oauth signup]
            if (Yii::$app->getModule('user')->enableSignup && Yii::$app->getModule('user')->enableOauthSignup) {

                $model = new SignupForm(['scenario' => SignupForm::SCENARIO_OAUTH]);

                if (Yii::$app->getModule('user')->enableOauthSignupValidation && Yii::$app->getModule('user')->enableSignupWithUsername) {
                    $model->username = strtolower(strtr($client->fullname, array(' '=>''))); ///simply remove whitespace and convert to lowercase
                }
                if (Yii::$app->getModule('user')->enableOauthSignupValidation && Yii::$app->getModule('user')->enableSignupWithEmail) {
                    $model->email = $client->email;
                }

                if ($user = $model->signup(Yii::$app->getModule('user')->enableOauthSignupValidation)) {

                    ///Add a new record to the oauth database table.
                    $auth = new \yongtiger\user\models\Oauth(['user_id' => $user->id]);
                    $auth->attributes = $client->getUserInfos();   ///Massive Assignment @see http://www.yiiframework.com/doc-2.0/guide-structure-models.html#massive-assignment
                    $auth->save(false);

                } else {

                    ///Pass the authentication error message to the signup page.
                    ///@see http://p2code.com/post/yii2-facebook-login-step-by-step-4
                    ///@see http://www.hafidmukhlasin.com/2014/10/29/yii2-super-easy-to-create-login-social-account-with-authclient-facebook-google-twitter-etc/
                    Yii::$app->session['signup-form'] = $model;
                    // Yii::$app->session['auth-client'] = $client; ///Note: `$client` object can not be saved in session! Anonymous functions can not be serialized
                    Yii::$app->session['auth-client'] = $client->getUserInfos();
                    $this->action->successUrl = Url::to(['registration/signup']);
                    return;
                }

            } else {
                Yii::$app->session->addFlash('danger', Module::t('user', 'Login failed! A user bound to this oAuth client was not found.'));
                $this->action->successUrl = $this->action->cancelUrl;
                return;
            }

        } else {

            ///update oauth
            $auth = $user->oauths[0];
            foreach ($client->getUserInfos() as $key => $value) {
                if ($auth->$key != $value) {
                    $auth->$key = $value;
                }
            }
            $auth->save(false);

        }

        ///login
        $model = new LoginForm();
        $model->setUser($user);
        if (!$model->login(false)) {
            $this->action->successUrl = $this->action->cancelUrl;
        }

    }

    ///[Yii2 uesr:oauth]///？？？？？
    /**
     * Tries to connect social account to user.
     *
     * @param ClientInterface $client
     */
    public function connect(\yongtiger\authclient\clients\IAuth $client)
    {
        /** @var Account $account */
        $account = \Yii::createObject(Account::className());
        $event   = $this->getAuthEvent($account, $client);
        $this->trigger(self::EVENT_BEFORE_CONNECT, $event);
        $account->connectWithUser($client);
        $this->trigger(self::EVENT_AFTER_CONNECT, $event);
        $this->action->successUrl = Url::to(['/user/settings/networks']);
    }
}
