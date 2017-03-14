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

    <!--///[v0.17.2 (profile birthday:DatePicker)]@see http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html-->
    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control'],
    ]) ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'education')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'revenue')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
