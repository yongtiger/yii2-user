<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\User */
/* @var $form yii\widgets\ActiveForm */

$dropDownListRoles = [User::ROLE_USER => Module::t('user', 'role_user')];
$me = Yii::$app->user->identity;
if ($me['role'] === User::ROLE_SUPER_MODERATOR) {
    $dropDownListRoles += [User::ROLE_MODERATOR => Module::t('user', 'role_moderator')];
} else if ($me['role'] === User::ROLE_ADMIN) {
    $dropDownListRoles += [User::ROLE_MODERATOR => Module::t('user', 'role_moderator')];
    $dropDownListRoles += [User::ROLE_SUPER_MODERATOR => Module::t('user', 'role_super_moderator')];
}

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'user-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>
    <?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
    <input type="password" style="display:none;" /><!--[Fix#password in Firefox] @see http://www.xuebuyuan.com/1836687.html-->
    <?= $form->field($model, 'password')->passwordInput()->hint(Module::t('user', 'The password only contains letters ...')); ?>

    <?= $form->field($model, 'email')->input('email'); ?>

    <?= $form->field($model, 'role')->dropDownList($dropDownListRoles, ['prompt' => Module::t('user', '(Please select ...)')]); ?>

    <?= $form->field($model, 'status')->dropDownList([User::STATUS_INACTIVE => Module::t('user', 'inactive'), User::STATUS_ACTIVE => Module::t('user', 'active')], ['prompt' => Module::t('user', '(Please select ...)')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('user', 'Create') : Module::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
