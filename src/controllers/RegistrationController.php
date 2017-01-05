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
use yongtiger\user\models\SignupForm;
use yongtiger\user\Module;

/**
 * Site controller
 */
class RegistrationController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'signup';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
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

            ///[Yii2 uesr:verifycode]
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 36, ///need to be adjusted according to the specific verification code bit
                'width' => 96,
                'maxLength' =>6,    ///random display minLength-maxLength bits of verification code
                'minLength' =>4,
                'testLimit'=>5,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,    ///automatically display a fixed test code, easy to copy the verification code
            ],

        ];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        $load = $model->load(Yii::$app->request->post());

        ///[Yii2 uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($load && ($user = $model->signup()) !== null) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
