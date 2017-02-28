<?php

use yii\helpers\Html;
use yongtiger\user\Module;
use kartik\widgets\ActiveForm;  ///??????
use kartik\daterange\DateRangePicker;   ///??????

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\VerifySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="verify-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'password_verified_date_range', [
        'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
        'options' => ['class' => 'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon' => true,
        'convertFormat' => true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d'
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'email_verified_date_range', [
        'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
        'options' => ['class' => 'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon' => true,
        'convertFormat' => true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d'
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'created_date_range', [
        'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
        'options' => ['class' => 'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon' => true,
        'convertFormat' => true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d'
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'updated_date_range', [
        'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
        'options' => ['class' => 'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon' => true,
        'convertFormat' => true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d'
            ]
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('user', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('user', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
