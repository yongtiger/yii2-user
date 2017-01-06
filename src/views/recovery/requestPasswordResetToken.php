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


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;

$this->title = Module::t('user', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('user', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form',

                ///[Yii2 uesr:Ajax validation]
                // 'enableClientValidation'=>false,        ///disable client validation
                'enableAjaxValidation'=>true,           ///enable Ajax validation
                // 'validateOnBlur'=>false,                ///disable validate on blur
                'validateOnSubmit' =>false,             ///disable validate on submit while using captcha & ajax!!!

            ]); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <!--///[Yii2 uesr:verifycode]-->
                <!--///captcha in module: /user/security/captcha-->
                <?= $form->field($model, 'verifyCode', [

                    'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                    'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                    
                    ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!
                    
                ])->widget(\yii\captcha\Captcha::className(), [
                    'captchaAction' => '/' . Yii::$app->controller->module->id . '/recovery/captcha',  ///default is 'site/captcha'
                    'imageOptions'=>['alt'=>Module::t('user', 'Verification Code'), 'title'=>Module::t('user', 'Click to change another verification code.')],
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('user', 'Send'), ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
