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
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;

$this->title = Module::t('user', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('user', 'Please fill out the following fields to signup:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup',

                ///[Yii2 uesr:Ajax validation]
                'enableAjaxValidation'=>true,           ///enable Ajax validation
                // 'enableClientValidation'=>false,        ///disable client validation
                // 'validateOnBlur'=>false,                ///disable validate on blur

            ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <!--///[Yii2 uesr:repassword]-->
                <?= $form->field($model,'repassword')->passwordInput() ?>

                <!--///[Yii2 uesr:verifycode]-->
                <!--///captcha in module: /user/security/captcha-->
                <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
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
