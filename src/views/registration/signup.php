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
 * @var $this yii\base\View
 * @var $form yii\widgets\ActiveForm
 * @var $model yongtiger\user\models\SignupForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;

$this->title = Module::t('user', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('user', 'Please fill out the following fields to signup:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'signup-form',

                ///[Yii2 uesr:Ajax validation]
                // 'enableClientValidation'=>false,        ///disable client validation
                'enableAjaxValidation'=>true,           ///enable Ajax validation
                // 'validateOnBlur'=>false,                ///disable validate on blur
                'validateOnSubmit' =>false,             ///disable validate on submit while using captcha & ajax!!!

            ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <!--///[Yii2 uesr:repassword]-->
                <?= $form->field($model,'repassword')->passwordInput() ?>

                <!--///[Yii2 uesr:verifycode]-->
                <!--///captcha in module: /user/security/captcha-->
                 <?= $form->field($model, 'verifyCode', [

                    'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                    'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                    
                    ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!
                    
                ])->widget(\yii\captcha\Captcha::className(), [
                    'captchaAction' => '/' . Yii::$app->controller->module->id . '/registration/captcha',  ///default is 'site/captcha'
                    'imageOptions'=>['alt'=>Module::t('user', 'Verification Code'), 'title'=>Module::t('user', 'Click to change another verification code.')],
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('user', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
