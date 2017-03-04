<?php

use yii\helpers\Html;
use yongtiger\user\Module;
use kartik\widgets\ActiveForm;  ///??????
use kartik\daterange\DateRangePicker;   ///??????
use yii\jui\DatePicker;

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

    <!--///[v0.17.2 (profile birthday:DatePicker)]@see http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html-->
    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control'],   ///[v0.17.4 (fix# profile birthday:DatePicker)]
    ]) ?>

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
