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
use yongtiger\user\models\Profile;
use yongtiger\user\models\ProfileSearch;
use yongtiger\user\models\User;

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
                        'actions' => ['view', 'avatar', 'crop-avatar'], ///[v0.21.0 (ADD# update avatar)]
                        'roles' => ['@'],
                    ],

                    [   ///[v0.17.0 (AccessControl of update profile and remove update verify)]
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
        ///[v0.21.0 (ADD# update avatar)]
            'crop-avatar'=>[
                'class' => 'yongtiger\cropperavatar\actions\CropAvatarAction',
                'config'=>[
                    // Default width of the destination image
                    'dstImageWidth' => 200,

                    // Default height of the destination image
                    'dstImageHeight' => 200,

                    // Default width of the middle image, empty means no generating
                    'middleImageWidth'=> 100,

                    // Default height of the middle image, empty means no generating
                    'middleImageHeight'=> 100,

                    // Default width of the small image, empty means no generating
                    'smallImageWidth' => 50,

                    // Default height of the small image, empty means no generating 
                    'smallImageHeight' => 50,

                    // Avatar upload path
                    'dstImageFilepath' => Yii::$app->user->isGuest ? '@webroot/uploads/avatar/0' : '@webroot/uploads/avatar/' . Yii::$app->user->identity->id,

                    // Avatar uri
                    'dstImageUri' => Yii::$app->user->isGuest ? '@web/uploads/avatar/0' : '@web/uploads/avatar/' . Yii::$app->user->identity->id,

                    // Avatar upload file name
                    'dstImageFilename' => date('YmdHis'),

                    // The file name suffix of the original image, empty means no generating
                    'original' => 'original',
                ],
            ],
        ];
    }

    ///[v0.21.0 (ADD# update avatar)]
    /**
     * Updates avatar in an existing Profile model.
     * @return mixed
     */
    public function actionAvatar()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = Profile::SCENARIO_AVATAR;
        $load = $model->load(Yii::$app->request->post());

        ///[yii2-uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        ///[v0.18.4 (frontend user menus)]
        $this->layout = 'main';

        if ($load && $user = $model->save()) {
            return $this->redirect(['/user/default/index']);
        } else {
            return $this->render('/avatar/update', [
                'model' => $model,
            ]);
        }
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

    /**
     * Updates an existing Profile model.
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

        ///[v0.18.4 (frontend user menus)]
        if (Yii::$app->user->id == $id) {
            $this->layout = 'main';
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
