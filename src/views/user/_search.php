<?php

use yii\helpers\Html;
use yongtiger\user\models\User;
use yongtiger\user\Module;
use kartik\widgets\ActiveForm;  ///??????
use kartik\daterange\DateRangePicker;   ///??????

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\UserSearch */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?php echo $form->field($model, 'email') ?>

    <?php echo $form->field($model, 'status')->dropDownList(
            [User::STATUS_INACTIVE => Module::t('message', 'inactive'), User::STATUS_ACTIVE => Module::t('message', 'active')], 
            ['prompt'=>Module::t('message', '(Please select ...)')]
        );
    ?>

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
