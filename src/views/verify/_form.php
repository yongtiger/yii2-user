<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Verify */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="verify-form">

    <?php $form = ActiveForm::begin(['id' => 'verify-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>

    <?= $form->field($model, 'password_verified_at')->textInput(['autofocus' => true]) ?><!--///?????time stamp in ActiveForm-->

    <?= $form->field($model, 'email_verified_at')->textInput() ?><!--///?????time stamp in ActiveForm-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('user', 'Create') : Module::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
