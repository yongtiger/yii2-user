<?php ///[Yii2 uesr:preference]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yongtiger\user\models\Preference;
use yongtiger\user\models\PreferenceSearch;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/**
 * PreferenceController implements the CRUD actions for Preference model.
 */
class PreferenceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['permission_access_app-backend'],   ///[v0.17.1 (AccessControl `permission_access_app-backend` of update and verify)]
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view', 'create'],
                        'roles' => ['@'],
                    ],

                    [///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            $useId = Yii::$app->request->get('id');
                            if (empty($model = Preference::findOne($useId))) {    ///gets the manipulated User object
                                Yii::$app->getResponse()->redirect(['user/preference/create'])->send();
                                die;
                            }
                            $user = $model->user;
                            return
                                $me->id == $user->id  ///if the manipulated user is `me`, update is allowed
                                ||
                                in_array(User::ROLE_ADMIN, $me->roles)  ///if `me` is `ROLE_ADMIN`, update is allowed
                                ||
                                in_array(User::ROLE_SUPER_MODERATOR, $me->roles) &&
                                    (!in_array(User::ROLE_ADMIN, $user->roles) && !in_array(User::ROLE_SUPER_MODERATOR, $user->roles)) ///if `me` is `ROLE_SUPER_MODERATOR` and the manipulated user is not `ROLE_ADMIN` nor `ROLE_SUPER_MODERATOR`, update is allowed
                                // || ... more rules as you customize
                            ;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Preference models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Preference model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        ///[v0.18.4 (frontend user menus)]
        if (empty(Yii::$app->isAdminEnd) && Yii::$app->user->id == $id) {
            $this->layout = 'main';
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    ///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]
    /**
     * Creates a new Preference model.
     *
     * If creation is successful, the browser will be redirected to the 'update' page.
     *
     * @see http://www.yiiframework.com/doc-2.0/guide-input-multiple-models.html
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Preference();
        
        $load = $model->load(Yii::$app->request->post());

        ///[yii2-uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('message', 'Successfully created.'));
            $this->redirect(['update', 'id' => $model->user_id]);
        } else {
            $this->layout = 'main';
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Preference model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $timezones = \DateTimeZone::listAbbreviations();
        $model = $this->findModel($id);
        $model->scenario = Preference::SCENARIO_UPDATE;    ///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]
        $load = $model->load(Yii::$app->request->post());

        ///[yii2-uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('message', 'Successfully updated.'));
        }

        ///[v0.18.4 (frontend user menus)]
        if (Yii::$app->user->id == $id) {
            $this->layout = 'main';
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Preference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Preference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Preference::findOne($id)) !== null) {    ///??????memory or query cache
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
