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
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yongtiger\user\Module;
use yongtiger\user\models\PasswordResetRequestForm;
use yongtiger\user\models\ResetPasswordForm;

/**
 * RecoveryController Controller
 *
 * @package yongtiger\user\controllers
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
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['request-password-reset', 'reset-password'],  ///except capcha
                'rules' => [
                    [
                        'actions' => ['request-password-reset', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions =[];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableRequestPasswordResetWithCaptcha) {
            $actions = array_merge($actions, ['captcha' => Yii::$app->getModule('user')->captcha]);
        }

        return $actions;
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
        if (Yii::$app->getModule('user')->enableRequestPasswordResetAjaxValidation) {
            ///Note: Should be handled as soon as possible ajax!
            ///Note: CAPTCHA validation should not be used in AJAX validation mode.
            ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        if ($load && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->addFlash('success', Module::t('user', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->addFlash('error', Module::t('user', 'Sorry, we are unable to reset password ...'));
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
            Yii::$app->session->addFlash('success', Module::t('user', 'New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
