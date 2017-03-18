<?php ///[Yii2 user]

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
use yii\filters\AccessControl;

/**
 * Default Controller
 *
 * @package yongtiger\user\controllers
 */
class DefaultController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {  ///[v0.18.5 (isAdminEnd)]
                            return empty(Yii::$app->isAdminEnd);
                        }
                    ],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * Displays user homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main';   ///[v0.18.4 (frontend user menus)]
        return $this->render('index');
    }
}
