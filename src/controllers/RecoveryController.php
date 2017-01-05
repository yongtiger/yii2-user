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
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yongtiger\user\models\PasswordResetRequestForm;
use yongtiger\user\models\ResetPasswordForm;
use yongtiger\user\Module;

/**
 * Site controller
 */
class RecoveryController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'requestPasswordReset';

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
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        $load = $model->load(Yii::$app->request->post());

        ///[Yii2 uesr:Ajax validation]
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($load && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Module::t('user', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Module::t('user', 'Sorry, we are unable to reset password ...'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Module::t('user', 'New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
