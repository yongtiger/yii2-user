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
use yii\authclient\AuthAction;
use yongtiger\user\Module;

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
                'rules' => [
                    [
                        'actions' => ['index', 'change'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableAccountChangeWithCaptcha) {
            $behaviors['access']['rules'][0]['actions'] = array_merge($behaviors['access']['rules'][0]['actions'], ['captcha']);
        }

        ///[Yii2 uesr:oauth]
        if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)) {
            $behaviors['access']['rules'][0]['actions'] = array_merge($behaviors['access']['rules'][0]['actions'], ['auth']);
        }
        
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
            $actions = array_merge($actions ,['captcha' => Yii::$app->getModule('user')->captcha]);
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
        ///Filter input. @see http://www.yiiframework.com/doc-2.0/guide-security-best-practices.html
        if (!in_array($item, ['username', 'email', 'password'])) {
            Yii::$app->session->addFlash('error', Module::t('message', 'Invalid action!'));
            return $this->redirect(['account/index']);
        }

        $changeItemFormName = 'yongtiger\user\models\Change' . ucfirst($item) . 'Form';
        $model = new $changeItemFormName();

        $load = $model->load(Yii::$app->request->post());

        ///[Yii2 uesr:Ajax validation]
        if (Yii::$app->getModule('user')->enableAccountChangeAjaxValidation && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $changeItem = 'change' . ucfirst($item);
        if ($load && $user = $model->$changeItem()) {
            return $this->redirect(['account/index']);
        }

        return $this->render('change', ['item' => $item, 'model' => $model]);
    }
}
