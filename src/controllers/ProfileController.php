<?php ///[Yii2 uesr:profile]

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
use yii\helpers\ArrayHelper;
use yongtiger\user\models\Profile;
use yongtiger\user\models\ProfileSearch;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
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

                    [
                        'allow' => true,
                        'actions' => ['avatar', 'crop-avatar'], ///[v0.21.0 (ADD# update avatar)]
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {  ///[v0.18.5 (isAdminEnd)]
                            return empty(Yii::$app->isAdminEnd);
                        }
                    ],

                    [   ///[v0.17.0 (AccessControl of update profile and remove update verify)]
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            $useId = Yii::$app->request->get('id');
                            if (empty($model = Profile::findOne($useId))) {    ///gets the manipulated User object
                                Yii::$app->getResponse()->redirect(['user/profile/create'])->send();    ///[v0.24.0 (ADD# actionCreate())]
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            ///[v0.21.0 (ADD# update avatar)]///[v0.24.4 (ADD# cropAvatar)]
            'crop-avatar' => ArrayHelper::merge([
                'successCallback' => [$this, 'saveAvatar'],
            ], Yii::$app->getModule('user')->cropAvatar),
        ];
    }

    ///[v0.21.2 (CHG# AvatarWidget::widget)]
    /**
     * CropAvatarAction successCallback.
     *
     * @param array $result
     * @param string $isInputWidget If set, means that `isInputWidget`, usually not to save the avatar operation
     */
    public function saveAvatar($result, $isInputWidget)  ///[isInputWidget]tell action's successCallback not to save the avatar operation.
    {
        if (empty($isInputWidget)) {
            // save avatar $result into user table ...
            $model = $this->findModel(Yii::$app->user->id);
            $model->scenario = Profile::SCENARIO_AVATAR;
            $model->avatar = $result['params']['dstImageFilename'] . $result['params']['extension'];
            $model->save();
        }
        return;
    }

    ///[v0.21.2 (CHG# AvatarWidget::widget)]
    /**
     * Updates avatar in an existing Profile model.
     * @return mixed
     */
    public function actionAvatar()
    {

        ///[v0.18.4 (frontend user menus)]
        $this->layout = 'main';

        return $this->render('/avatar/update');
    }

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Profile model.
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

    ///[v0.24.0 (ADD# actionCreate())]
    /**
     * Creates a new Profile model.
     *
     * If creation is successful, the browser will be redirected to the 'update' page.
     *
     * @see http://www.yiiframework.com/doc-2.0/guide-input-multiple-models.html
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Profile();
        
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
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Profile::SCENARIO_UPDATE;    ///[v0.24.1 (ADD# SCENARIO_UPDATE)]
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
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {    ///??????memory or query cache
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
