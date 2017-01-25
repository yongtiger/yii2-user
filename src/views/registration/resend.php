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
 * @var $model yongtiger\user\models\ResendForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;

$this->title = Module::t('user', 'Resend e-mail activation');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration-resend">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('user', 'Please fill out your registration email. A link to activation will be sent there.') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'resend-form',

                ///[Yii2 uesr:Ajax validation]
                'enableClientValidation' => Yii::$app->getModule('user')->enableSignupClientValidation,
                'enableAjaxValidation' => Yii::$app->getModule('user')->enableSignupAjaxValidation,
                'validateOnBlur' => Yii::$app->getModule('user')->enableSignupValidateOnBlur,
                ///disable validate on submit while using captcha & ajax!!!
                'validateOnSubmit' => !(
                    Yii::$app->getModule('user')->enableSignupAjaxValidation && 
                    Yii::$app->getModule('user')->enableSignupWithCaptcha
                ) && Yii::$app->getModule('user')->enableSignupValidateOnSubmit,

            ]); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <!--///[Yii2 uesr:verifycode]-->
                <?= $form->field($model, 'verifyCode', [

                    'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                    'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html

                    ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!

                ])->widget(Yii::$app->getModule('user')->captchaActiveFieldWidget['class'], array_merge(Yii::$app->getModule('user')->captchaActiveFieldWidget, [

                    ///captcha in module, e.g. `/user/registration/captcha`
                    'captchaAction' => '/' . Yii::$app->controller->module->id . '/registration/captcha',  ///default is 'site/captcha'

                ])) ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('user', 'Resend'), ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
