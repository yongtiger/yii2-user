<?php ///[Yii2 uesr:preference]

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;
use yongtiger\timezone\TimeZone;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Preference */
/* @var $form yii\widgets\ActiveForm */

$timezones = TimeZone::timezone_list(TimeZone::SORT_BY_OFFSET); ///[v0.19.1 (ADD# yongtiger\timezone\TimeZone::timezone_list())]

?>
<div class="preference-form">

    <?php $form = ActiveForm::begin(['id' => 'preference-form',

        ///[yii2-uesr:Ajax validation]
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnSubmit' => true

    ]); ?>

    <?= $form->field($model, 'locale')->dropDownList(['en-US'=> 'English', 'zh-CN'=> '中文(简体)'], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select locale ...)'),
        'autofocus' => true,
    ]) ?>

    <?= $form->field($model, 'time_zone')->dropDownList($timezones, [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select time offset ...)'),
    ]) ?>

    <?= $form->field($model, 'datetime_format')->dropDownList(['aaa'=> 'bbb'], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select datetime format ...)'),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('message', 'Create') : Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
