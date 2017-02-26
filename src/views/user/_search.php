<?php

use yii\helpers\Html;
use yongtiger\user\models\User;
use kartik\daterange\DateRangePicker;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>
    <!--///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]
    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'password_hash') ?>

    <?= $form->field($model, 'token') ?>
    ///[http://www.brainbook.cc]-->
    <?php echo $form->field($model, 'email') ?>

    <!--///[yii2-admin-boot_v0.5.14_f0.5.13_user_dropdownlist_role]-->
    <?php ///echo $form->field($model, 'role') ?><!--///[yii2-admin-boot_v0.5.1_f0.5.0_user_add_role_field]-->
    <?php echo $form->field($model, 'role')->dropDownList(
            [User::ROLE_ADMIN => 'ROLE_ADMIN', User::ROLE_SUPER_MODERATOR => 'ROLE_SUPER_MODERATOR', User::ROLE_MODERATOR => 'ROLE_ADMIN', User::ROLE_USER => 'ROLE_USER'], 
            ['prompt'=>'Select...']
        );
     ?>
     <!--///[http://www.brainbook.cc]-->

    <!--///[yii2-admin-boot_v0.5.13_f0.5.12_user_dropdownlist_status]-->
    <?php echo $form->field($model, 'status')->dropDownList(
            [User::STATUS_INACTIVE => 'STATUS_INACTIVE', User::STATUS_ACTIVE => 'STATUS_ACTIVE'], 
            ['prompt'=>'Select...']
        );
     ?>
     <!--///[http://www.brainbook.cc]-->

     <!--///[yii2-admin-boot_v0.5.16_f0.5.15_user_daterangepicker]-->
    <?php ///echo $form->field($model, 'created_at') ?>
    <?= $form->field($model, 'created_date_range', [
        'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
        'options'=>['class'=>'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon'=>true,
        'convertFormat'=>true,
        'pluginOptions'=>[
            'locale'=>[
                'format'=>'Y-m-d'
            ]
        ]
    ]) ?>

    <?php ///echo $form->field($model, 'updated_at') ?>
    <?= $form->field($model, 'updated_date_range', [
        'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
        'options'=>['class'=>'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon'=>true,
        'convertFormat'=>true,
        'pluginOptions'=>[
            'locale'=>[
                'format'=>'Y-m-d'
            ]
        ]
    ]) ?>
     <!--///[http://www.brainbook.cc]-->

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
