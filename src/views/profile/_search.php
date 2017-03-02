<?php

use yii\helpers\Html;
use yongtiger\user\Module;
use kartik\widgets\ActiveForm;  ///??????
use kartik\daterange\DateRangePicker;   ///??????

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\ProfileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'fullname') ?>

    <?= $form->field($model, 'firstname') ?>

    <?= $form->field($model, 'lastname') ?>

    <?= $form->field($model, 'gender') ?>

    <?= $form->field($model, 'language') ?>

    <?= $form->field($model, 'avatar') ?>

    <?= $form->field($model, 'link') ?>

    <?= $form->field($model, 'birthyear') ?>

    <?= $form->field($model, 'birthmonth') ?>

    <?= $form->field($model, 'birthday') ?>

    <?= $form->field($model, 'country') ?>

    <?= $form->field($model, 'province') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'telephone') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'graduate') ?>

    <?= $form->field($model, 'education') ?>

    <?= $form->field($model, 'company') ?>

    <?= $form->field($model, 'position') ?>

    <?= $form->field($model, 'revenue') ?>

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
        <?= Html::submitButton(Module::t('message', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('message', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
