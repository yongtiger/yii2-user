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

namespace yongtiger\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yongtiger\user\Module;
use yongtiger\user\models\User;
use yongtiger\user\models\Oauth;
use yongtiger\user\helpers\SecurityHelper;
use yongtiger\user\models\ActivationForm;

/**
 * Account Controller
 *
 * @package yongtiger\user\controllers
 */
class AccountController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'change', 'send-verification-email', 'verify-email'],  ///except capcha
                'rules' => [
                    [
                        'actions' => ['index', 'change', 'send-verification-email', 'verify-email'],
                        'allow' => true,
                        'roles' => ['@'],
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

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableAccountChangeWithCaptcha) {
            $actions = array_merge($actions, ['captcha' => Yii::$app->getModule('user')->captcha]);
        }

        ///[Yii2 uesr:oauth]
        if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)) {
            $auth = [
                'class' => 'yii\authclient\AuthAction',
                'successUrl' => ['user/account/index'],
                'cancelUrl' => ['user/account/index'],
                'successCallback' => [$this, 'connect'],
            ];

            $actions = array_merge($actions, ['auth' => ArrayHelper::merge(Yii::$app->getModule('user')->auth, $auth)]);
        }

        return $actions;
    }

    /**
     * Displays user account homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        ///[Yii2 uesr:account oauth]
        if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)) {
            $oauths = [];
            foreach (Yii::$app->user->identity->oauths as $auth) {
                $oauths[] = $auth->provider;
            }
            return $this->render('index', ['oauths' => $oauths]);
        } else {
            return $this->render('index');
        }
    }

    /**
     * Changes item.
     *
     * @param string $item
     * @return mixed
     */
    public function actionChange($item)
    {
        ///@see http://www.yiiframework.com/doc-2.0/guide-security-best-practices.html
        if (!in_array($item, ['username', 'email', 'password'])) {
            Yii::$app->session->addFlash('warning', Module::t('user', 'Invalid action!'));
            return $this->redirect(['account/index']);
        }

        $changeItemFormName = 'yongtiger\user\models\Change' . ucfirst($item) . 'Form';
        $model = new $changeItemFormName();

        $load = $model->load(Yii::$app->request->post());

        ///[Yii2 uesr:Ajax validation]
        if (Yii::$app->getModule('user')->enableAccountChangeAjaxValidation) {
            ///Note: Should be handled as soon as possible ajax!
            ///Note: CAPTCHA validation should not be used in AJAX validation mode.
            ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        $changeItem = 'change' . ucfirst($item);
        if ($load && $user = $model->$changeItem()) {
            return $this->redirect(['account/index']);
        }

        return $this->render('change', [
            'item' => $item,
            'model' => $model,
        ]);
    }

    ///[Yii2 uesr:account verify email]
    /**
     * Sends verification email.
     *
     * @return mixed
     */
    public function actionSendVerificationEmail()
    {
        $user = Yii::$app->user->identity;
        $user->activation_key = SecurityHelper::generateExpiringRandomKey(Yii::$app->getModule('user')->signupWithEmailActivationExpire);

        if ($user->save(false)) {

            Yii::$app
                ->mailer
                ->compose(
                    ['html' => Yii::$app->getModule('user')->accountVerificationEmailComposeHtml, 'text' => Yii::$app->getModule('user')->accountVerificationEmailComposeText],
                    ['user' => $user]
                )
                ->setFrom(Yii::$app->getModule('user')->accountVerificationEmailSetFrom)
                ->setTo($user->email)
                ->setSubject(Module::t('user', 'Verification mail from ') . Yii::$app->name)
                ->send();

            Yii::$app->session->addFlash('warning', Module::t('user', 'Please check your email [{youremail}] to verify your email.', ['youremail' => $user->email]));

        }

        return $this->redirect(['account/index']);
    }

    ///[Yii2 uesr:account verify email]
    /**
     * Verifies user account email.
     *
     * @param string $key Activation key.
     */
    public function actionVerifyEmail($key)
    {
        $model = new ActivationForm(['activation_key' => $key]);

        if ($model->verifyEmail()) {
            return $this->redirect(['account/index']);
        }

        return $this->goHome();
    }
}
