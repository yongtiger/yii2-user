<?php ///[Yii2 uesr:status]

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
use yongtiger\user\models\Status;
use yongtiger\user\models\StatusSearch;
use yongtiger\user\models\User;

/**
 * StatusController implements the CRUD actions for Status model.
 */
class StatusController extends Controller
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
                        'matchCallback' => function ($rule, $action) {  ///[v0.18.5 (isAdminEnd)]
                            return !empty(Yii::$app->isAdminEnd);
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],

                    [   ///[v0.17.0 (AccessControl of update profile and remove update verify)]
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['permission_access_app-backend'],
                        'matchCallback' => function ($rule, $action) {
                            if (empty(Yii::$app->isAdminEnd)) return false;   ///[v0.18.5 (isAdminEnd)]
                            $me = Yii::$app->user->identity;
                            $useId = Yii::$app->request->get('id');
                            $user = $this->findModel($useId)->user;   ///gets the manipulated User object

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
     * Lists all Status models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Status model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        ///[v0.18.4 (frontend user menus)]
        if (Yii::$app->user->id == $id) {
            $this->layout = 'main';
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Status model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $load = $model->load(Yii::$app->request->post());

        ///[yii2-uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $user = $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Status model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Status the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Status::findOne($id)) !== null) { ///??????memory or query cache
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
