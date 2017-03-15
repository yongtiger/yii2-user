<?php ///[Yii2 uesr:preference]

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Preference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="preference-form">

    <?php $form = ActiveForm::begin(['id' => 'preference-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>

    <?= $form->field($model, 'locale')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

    <?= $form->field($model, 'time_offset')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime_format')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('message', 'Create') : Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
