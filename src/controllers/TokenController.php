<?php ///[Yii2 uesr:token]

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
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;
use yongtiger\user\models\SendTokenForm;

/**
 * Token Controller
 *
 * @package yongtiger\user\controllers
 */
class TokenController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'send-token';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions =[];

        ///[Yii2 uesr:verifycode]
        if (Yii::$app->getModule('user')->enableSendTokenWithCaptcha) {
            $actions = array_merge($actions ,['captcha' => Yii::$app->getModule('user')->captcha]);
        }

        return $actions;
    }

    /**
     * Filters of `actionSendToken($type)`.
     *
     * @param string $type
     * @return bool Whether allow to run this action
     */
    protected function filterSendToken($type)
    {
        switch ($type) {
            case SendTokenForm::SCENARIO_ACTIVATION:
                return Yii::$app->user->isGuest && Yii::$app->getModule('user')->enableSignupWithEmailActivation;
            case SendTokenForm::SCENARIO_RECOVERY:
                return Yii::$app->user->isGuest && Yii::$app->getModule('user')->enableRecoveryPassword;    ///[v0.9.7 (backend:enableRecoveryPassword)]
            case SendTokenForm::SCENARIO_VERIFICATION:
                return !Yii::$app->user->isGuest;
            default:
        }
        return false;
    }

    /**
     * Sends a token by given `$type`.
     *
     * @param string $type
     * @return mixed
     */
    public function actionSendToken($type)
    {
        ///Filter input. @see http://www.yiiframework.com/doc-2.0/guide-security-best-practices.html
        if (!$this->filterSendToken($type)) {
            Yii::$app->session->addFlash('error', Module::t('user', 'Invalid action!'));
            return Yii::$app->user->isGuest ? $this->goHome() : $this->redirect(['account/index']);
        }

        $model = new SendTokenForm(['scenario' => $type]);

        ///[Yii2 uesr:token]SendTokenWithoutLoad
        if (Yii::$app->getModule('user')->enableSendTokenWithoutLoad && !Yii::$app->user->isGuest) {
            return $model->send() ? $this->redirect(['account/index']) : $this->render('sendToken', ['model' => $model, 'type' => $type]);
        }

        $load = $model->load(Yii::$app->request->post());

        ///[Yii2 uesr:Ajax validation]
        if (Yii::$app->getModule('user')->enableSendTokenAjaxValidation && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $model->send()) {
            return $this->redirect(['account/index']);
        }

        return $this->render('sendToken', ['model' => $model, 'type' => $type]);
    }

    /**
     * Filters of `actionHandleToken($type, $token)'.
     *
     * @param string $type
     * @param string $token
     * @return bool Whether allow to run this action
     */
    protected function filterHandleToken($type, $token)
    {
        switch ($type) {
            case SendTokenForm::SCENARIO_ACTIVATION:
                return Yii::$app->user->isGuest && Yii::$app->getModule('user')->enableSignupWithEmailActivation;
            case SendTokenForm::SCENARIO_RECOVERY:
                return Yii::$app->user->isGuest;
            case SendTokenForm::SCENARIO_VERIFICATION:
                return !Yii::$app->user->isGuest;
            default:
        }
        return false;
    }

    /**
     * Handles a token by given `$type` and `$token`.
     *
     * @param string $type
     * @param string $token
     * @return mixed
     */
    public function actionHandleToken($type, $token)
    {
        ///Filter input. @see http://www.yiiframework.com/doc-2.0/guide-security-best-practices.html
        if (!$this->filterHandleToken($type, $token)) {
            Yii::$app->session->addFlash('error', Module::t('user', 'Invalid action!'));
            return Yii::$app->user->isGuest ? $this->goHome() : $this->redirect(['account/index']);
        }

        $model = new TokenHandler(['scenario' => $type, 'token' => $token]);

        if ($model->handle()) {
            switch ($type) {
                case TokenHandler::SCENARIO_ACTIVATION:
                    Yii::$app->user->login($model->getUser());
                    break;
                case TokenHandler::SCENARIO_RECOVERY:
                    Yii::$app->user->login($model->getUser());
                    return $this->redirect(['account/change', 'item' => 'password']);
                case TokenHandler::SCENARIO_VERIFICATION:
                    break;
                default:
            }
        }

        return $this->redirect(['account/index']);
    }
}
