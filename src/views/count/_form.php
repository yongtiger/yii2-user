<?php ///[Yii2 uesr:count]

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Count */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="count-form">

    <?php $form = ActiveForm::begin(['id' => 'count-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>

    <?= $form->field($model, 'login_count')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'banned_count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('message', 'Create') : Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
