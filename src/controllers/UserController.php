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
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yongtiger\user\models\User;
use yongtiger\user\models\UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deleteIn' => ['POST'], ///[yii2-admin:user_deleteIn]
                ],
            ],

            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['update','delete',
                            'delete-in' ///[yii2-admin:user_deleteIn][BUG]批量删除仍然能删除自己
                        ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            ///$useId = Yii::$app->getRequest()->queryParams['id'];///Request请求中的id即为用户ID
                            $useId = Yii::$app->request->get('id');
                            ///$user = User::findOne(['id' => $useId]);///获取被操作的User对象
                            $user = $this->findModel($useId);

                            return
                                $me['id'] == $user['id']  ///如果当前用户是自己，则允许更新操作
                                ||
                                $me['role'] == User::ROLE_ADMIN ///如果当前用户是站长，则允许更新操作
                                ||
                                $me['role'] == User::ROLE_SUPER_MODERATOR &&
                                    ($user['role'] != User::ROLE_ADMIN && $user['role'] != User::ROLE_SUPER_MODERATOR) ///如果当前用户是超版，被操作用户不为站长或超版，则允许更新操作
                                ///|| ... 还可以继续增加规则
                            ;
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            ///$useId = Yii::$app->getRequest()->queryParams['id'];///Request请求中的id即为用户ID
                            $useId = Yii::$app->request->get('id');
                            ///$user = User::findOne(['id' => $useId]);///获取被操作的User对象
                            $user = $this->findModel($useId);

                            return
                                !( ///为方便理解逻辑，先用“或关系”列出不允许删除操作的条件，最后再取反
                                    $me['id'] == $user['id']      ///如果当前用户是自己，则不允许删除操作
                                    ||
                                    $me['role'] == User::ROLE_SUPER_MODERATOR &&
                                        ($user['role'] == User::ROLE_ADMIN || $user['role'] == User::ROLE_SUPER_MODERATOR)  ///如果当前用户是超版，被操作用户是站长或超版，则不允许删除操作
                                    ||
                                    $me['role'] == User::ROLE_MODERATOR  ///如果当前用户是版主，则不允许删除操作
                                    ||
                                    $me['role'] == User::ROLE_USER  ///如果当前用户是普通用户，则不允许删除操作
                                    ///|| ...还可以继续增加规则
                                )
                            ;
                        }
                    ],

                    ///[yii2-admin:user_deleteIn][BUG]批量删除仍然能删除自己
                    [
                        'allow' => true,
                        'actions' => ['delete-in'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            ///只允许站长、超级版主批量删除用户。在后面运行的actionDeleteIn()中继续判断批量删除的用户列表$selected
                            return $me['role'] == User::ROLE_ADMIN || $me['role'] == User::ROLE_SUPER_MODERATOR;
                        }
                    ],
                    ///[http://www.brainbook.cc]

                ],
            ],

        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功'); ///[yii2-admin-boot_v0.5.7_f0.5.6_move_out_yii2-flash]
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '更新成功'); ///[yii2-admin-boot_v0.5.7_f0.5.6_move_out_yii2-flash]
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ///[yii2-admin-boot_v0.4.1_f0.4.0_yii2-flash]
        $ret = $this->findModel($id)->delete();
        if($ret === false)
        {
            Yii::$app->session->setFlash('danger', '<b>Alert!</b> 删除失败(id='.$id.')'); ///[yii2-admin-boot_v0.5.7_f0.5.6_move_out_yii2-flash]
        }else{
            Yii::$app->session->setFlash('danger', '删除成功(id='.$id.')'); ///[yii2-admin-boot_v0.5.7_f0.5.6_move_out_yii2-flash]
        }
        ///[http://www.brainbook.cc]

        return $this->redirect(['index']);
    }

    ///[yii2-admin:user_deleteIn]
    /**
     * Deletes selected in post.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteIn()
    {
        $selected = Yii::$app->request->post('selected' , []);

        ///[yii2-admin:user_deleteIn][BUG]批量删除仍然能删除自己
        $arrSelected = explode(',', $selected);

        $me = Yii::$app->user->identity;
        $manager = Yii::$app->getAuthManager();
        $idsRoleAdmin = $manager->getUserIdsByRole(User::ROLE_ADMIN);
        $idsRoleSuperModerator = $manager->getUserIdsByRole(User::ROLE_SUPER_MODERATOR);
        
        $arrSelected = array_merge(array_diff($arrSelected, ///PHP技巧：从数组中删除元素

            [$me->id], ///删掉自己

            $idsRoleAdmin, ///删掉ROLE_ADMIN的所有用户id

            ///$idsRoleSuperModerator  ////删掉ROLE_SUPER_MODERATOR的所有用户id（即使当前用户是站长也不允许删除超版！）
            ($me->role == User::ROLE_ADMIN) ? [] : $idsRoleSuperModerator  ///如果当前用户是站长，则不去掉所有超版id

            )
        );

        $ret = User::deleteAll(['id' => $arrSelected]);
        ///[http://www.brainbook.cc]

        Yii::$app->session->setFlash('danger', '批量删除'.$ret.'条记录'); ///[yii2-admin-boot_v0.5.7_f0.5.6_move_out_yii2-flash]

        return $this->redirect(['index']);
    }
    ///[http://www.brainbook.cc]

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
