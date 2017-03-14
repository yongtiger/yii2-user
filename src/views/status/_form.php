<?php ///[Yii2 uesr:status]

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Status */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="status-form">

    <?php $form = ActiveForm::begin(['id' => 'status-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>

    <?= $form->field($model, 'registration_ip')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

    <?= $form->field($model, 'last_login_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_login_at')->textInput() ?><!--///?????time stamp in ActiveForm-->

    <?= $form->field($model, 'banned_at')->textInput() ?><!--///?????time stamp in ActiveForm-->

    <?= $form->field($model, 'banned_reason')->textArea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('message', 'Create') : Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
