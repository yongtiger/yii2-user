<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\User */
/* @var $form yii\widgets\ActiveForm */

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
    <?= $form->field($model, 'password')->passwordInput()->hint(Module::t('message', 'The password only contains letters ...')); ?>

    <?= $form->field($model, 'email')->input('email'); ?>

    <?= $form->field($model, 'status')->dropDownList([User::STATUS_INACTIVE => Module::t('message', 'inactive'), User::STATUS_ACTIVE => Module::t('message', 'active')], ['prompt' => Module::t('message', '(Please select ...)')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('message', 'Create') : Module::t('message', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
