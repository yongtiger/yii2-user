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

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model yongtiger\user\models\ResetPasswordForm 
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;

$this->title = Module::t('user', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recovery-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('user', 'Please choose your new password:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form',

                ///[Yii2 uesr:Ajax validation]
                'enableClientValidation' => Yii::$app->getModule('user')->enableRequestPasswordResetClientValidation,
                'enableAjaxValidation' => Yii::$app->getModule('user')->enableRequestPasswordResetAjaxValidation,
                'validateOnBlur' => Yii::$app->getModule('user')->enableRequestPasswordResetValidateOnBlur,
                ///disable validate on submit while using captcha & ajax!!!
                'validateOnSubmit' => !(
                    Yii::$app->getModule('user')->enableRequestPasswordResetAjaxValidation && 
                    Yii::$app->getModule('user')->enableRequestPasswordResetWithCaptcha
                ) && Yii::$app->getModule('user')->enableRequestPasswordResetValidateOnSubmit,

            ]); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <!--///[Yii2 uesr:repassword]-->
                <?= $form->field($model,'repassword')->passwordInput() ?>

                <!--///[Yii2 uesr:verifycode]-->
                <?php if (Yii::$app->getModule('user')->enableRequestPasswordResetWithCaptcha): ?>
                    <?= $form->field($model, 'verifyCode', [

                        'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                        'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                        
                        ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!
                        
                    ])->widget(Yii::$app->getModule('user')->captchaActiveFieldWidget['class'], array_merge(Yii::$app->getModule('user')->captchaActiveFieldWidget, [

                        ///captcha in module, e.g. `/user/recovery/captcha`
                        'captchaAction' => '/' . Yii::$app->controller->module->id . '/recovery/captcha',  ///default is 'site/captcha'

                    ])) ?>
                <?php endif; ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
