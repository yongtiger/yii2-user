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
                //'controller'=>'login',   ///The controller that owns this action
                // 'backColor'=>0xFFFFFF,  ///The background color. For example, 0x55FF00. Defaults to 0xFFFFFF, meaning white color.
                // 'foreColor'=>0x2040A0,  ///The font color. For example, 0x55FF00. Defaults to 0x2040A0 (blue color).
                // 'padding' => 5,     ///Padding around the text. Defaults to 2.
                // 'offset'=>-2,       ///The offset between characters. Defaults to -2. You can adjust this property in order to decrease or increase the readability of the captcha.
                'height' => 36,     ///The height of the generated CAPTCHA image. Defaults to 50. need to be adjusted according to the specific verification code bit
                'width' => 96,      ///The width of the generated CAPTCHA image. Defaults to 120.
                'maxLength' =>6,    ///The maximum length for randomly generated word. Defaults to 7.
                'minLength' =>4,    ///The minimum length for randomly generated word. Defaults to 6.
                'testLimit'=>5,     ///How many times should the same CAPTCHA be displayed. Defaults to 3. A value less than or equal to 0 means the test is unlimited (available since version 1.1.2).
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,    ///The fixed verification code. When this property is set, getVerifyCode() will always return the value of this property. This is mainly used in automated tests where we want to be able to reproduce the same verification code each time we run the tests. If not set, it means the verification code will be randomly generated.
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
        ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
        ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
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
