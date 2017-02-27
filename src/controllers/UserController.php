<?php ///[Yii2 uesr]

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
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yongtiger\user\models\User;
use yongtiger\user\models\UserSearch;
use yongtiger\user\Module;

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
                    'deleteIn' => ['POST'], ///[yii2-user:deleteIn]
                ],
            ],

            ///[yii2-user:role]
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['update', 'delete', 'create', 'delete-in'],  ///[yii2-user:deleteIn]
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            $useId = Yii::$app->request->get('id');
                            $user = $this->findModel($useId);   ///gets the manipulated User object

                            return
                                $me['id'] == $user['id']  ///if the manipulated user is `me`, update is allowed
                                ||
                                $me['role'] == User::ROLE_ADMIN ///if `me` is `ROLE_ADMIN`, update is allowed
                                ||
                                $me['role'] == User::ROLE_SUPER_MODERATOR &&
                                    ($user['role'] != User::ROLE_ADMIN && $user['role'] != User::ROLE_SUPER_MODERATOR) ///if `me` is `ROLE_SUPER_MODERATOR` and the manipulated user is not `ROLE_ADMIN` or `ROLE_SUPER_MODERATOR`, update is allowed
                                // || ... more rules as you customize
                            ;
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            $useId = Yii::$app->request->get('id');
                            $user = $this->findModel($useId);   ///gets the manipulated User object

                            return
                                !( ///to facilitate understanding of logic, first use `OR` lists the conditions that do not allow the deletion of the operation, and finally to reverse by `NOT`
                                    $me['id'] == $user['id']      ///if the manipulated user is `me`, delete is not allowed
                                    ||
                                    $me['role'] == User::ROLE_SUPER_MODERATOR &&
                                        ($user['role'] == User::ROLE_ADMIN || $user['role'] == User::ROLE_SUPER_MODERATOR)  ///if `me` is `ROLE_SUPER_MODERATOR` and the manipulated user is `ROLE_ADMIN` or `ROLE_SUPER_MODERATOR`, delete is not allowed
                                    ||
                                    $me['role'] != User::ROLE_ADMIN && $me['role'] != User::ROLE_SUPER_MODERATOR  ///if `me` is not `ROLE_ADMIN` nor `ROLE_SUPER_MODERATOR`, delete is not allowed
                                    // || ... more rules as you customize
                                )
                            ;
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create', 'delete-in'], ///[yii2-user:deleteIn]
                        'matchCallback' => function ($rule, $action) {
                            $me = Yii::$app->user->identity;
                            ///Only allows `ROLE_ADMIN` or `ROLE_SUPER_MODERATOR` to batch delete users.
                            ///In the subsequent operation of the `actionDeleteIn()` continue to determine the batch delete the user list `$selected`
                            return $me['role'] == User::ROLE_ADMIN || $me['role'] == User::ROLE_SUPER_MODERATOR;
                        }
                    ],

                ],
            ],
            ///[http://www.brainbook.cc]

        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());

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

        $model->scenario = 'create';    ///[yii2-user:password]password must be required to verify at `create`, and has not to be verified at `update`

        $load = $model->load(Yii::$app->request->post());

        ///[yii2-uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $user = $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('user', 'Successfully created.'));
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

        $load = $model->load(Yii::$app->request->post());

        ///[yii2-uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $user = $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('user', 'Successfully updated.'));
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
        $ret = $this->findModel($id)->delete();
        if($ret === false)
        {
            Yii::$app->session->setFlash('error', Module::t('user', 'Failed to delete!') . ' (ID = '.$id.')');
        }else{
            Yii::$app->session->setFlash('success', Module::t('user', 'Successfully deleted.') . ' (ID = '.$id.')');
        }

        return $this->redirect(['index']);
    }

    ///[yii2-user:deleteIn]
    /**
     * Deletes selected in post.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteIn()
    {
        $selected = Yii::$app->request->post('selected' , []);
        $arrSelected = explode(',', $selected);

        ///[yii2-user:role]
        $me = Yii::$app->user->identity;
        $manager = Yii::$app->getAuthManager();
        $idsRoleAdmin = $manager->getUserIdsByRole(User::ROLE_ADMIN);
        $idsRoleSuperModerator = $manager->getUserIdsByRole(User::ROLE_SUPER_MODERATOR);
        
        $arrSelected = array_merge(array_diff($arrSelected, ///PHP tips: remove the element from the array

            [$me->id], ///remove `me` from array `$arrSelected`

            $idsRoleAdmin, ///remove `ROLE_ADMIN` from array `$arrSelected`

            ($me->role == User::ROLE_ADMIN) ? [] : $idsRoleSuperModerator  ///if `me` is not `ROLE_ADMIN`, is not allowed to remove `ROLE_SUPER_MODERATOR`

        ));
        ///[http://www.brainbook.cc]

        $ret = User::deleteAll(['id' => $arrSelected]);

        $str = $ret > 0 ? ' (IDs [' . implode(', ', $arrSelected) . '])' : '';
        Yii::$app->session->setFlash('info', Module::t('user', 'Deleted {0} users.', $ret) . $str);

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
