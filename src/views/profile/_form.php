<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin(['id' => 'profile-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>
    
    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput() ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'avatar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthyear')->textInput() ?>

    <?= $form->field($model, 'birthmonth')->textInput() ?>

    <?= $form->field($model, 'birthday')->textInput() ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'graduate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'education')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'revenue')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
