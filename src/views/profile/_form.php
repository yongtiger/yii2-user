<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;
use yongtiger\user\Module;
use yongtiger\region\widgets\RegionWidget;
use yongtiger\region\models\Region;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Profile */
/* @var $form yii\widgets\ActiveForm */

///[uncheck radio button]
///@see http://www.mkyong.com/jquery/how-to-select-a-radio-button-with-jquery/
///@see http://wenda.so.com/q/1364789883063842
$this->registerJs(
<<<JS
    $('input:radio').on('dblclick', function() { 
        \$(this).attr('checked',false);
    });
JS
);

?>
<div class="profile-form">

    <?php $form = ActiveForm::begin(['id' => 'profile-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->radioList([1 => Module::t('message', 'Male'), 0 => Module::t('message', 'Female')]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <!--///[v0.17.2 (profile birthday:DatePicker)]@see http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html-->
    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'options' => ['class' => 'form-control'],
    ]) ?>

    <!--///?????need upgrade region-->
    <!--<?///= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>-->

    <!--///[v0.17.3 (profile region widget)]-->
    <?= $form->field($model, 'region')->widget(RegionWidget::className(), [
        'provinceAttribute' => 'province',
        'cityAttribute' => 'city',
        'districtAttribute' => 'district'
    ]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'graduate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'education')->dropDownList([
        'Primary School' => Module::t('message', 'Primary School'),
        'Junior High School' => Module::t('message', 'Junior High School'),
        'High School' => Module::t('message', 'High School'),
        'Secondary School' => Module::t('message', 'Secondary School'),
        'Bachelor' => Module::t('message', 'Bachelor'),
        'Master' => Module::t('message', 'Master'),
        'Doctor' => Module::t('message', 'Doctor'),
        'P.H.D.' => Module::t('message', 'P.H.D.'),
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Please select ...)'),
    ]) ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->dropDownList([
        'Staff' => Module::t('message', 'Staff'),
        'Engineer' => Module::t('message', 'Engineer'),
        'Secretary' => Module::t('message', 'Secretary'),
        'Manager' => Module::t('message', 'Manager'),
        'CEO' => Module::t('message', 'CEO'),
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Please select ...)'),
    ]) ?>

    <?= $form->field($model, 'revenue')->dropDownList([
        '0-10k' => Module::t('message', '0-10k'),
        '10k-20k' => Module::t('message', '10k-20k'),
        '20k-50k' => Module::t('message', '20k-50k'),
        '50k-100k' => Module::t('message', '50k-100k'),
        '100k+' => Module::t('message', '100k+'),
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Please select ...)'),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
