<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <!--///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]
    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
    ///[http://www.brainbook.cc]-->
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 20])->hint('留空表示保留原密码不更新') ?><!--///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]-->
    <!--///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]
    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>
    ///[http://www.brainbook.cc]-->
    <?= $form->field($model, 'email')->input('email') ?>
    
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

    <!--///[yii2-admin-boot_v0.4.3_f0.4.2_user_datetime]
    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>
    ///[http://www.brainbook.cc]-->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
