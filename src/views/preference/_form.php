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

    <?= $form->field($model, 'locale')->dropDownList([
        'en-US'=> 'English',
        'zh-CN'=> Module::t('message', 'zh-CN'),
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select locale ...)'),
        'autofocus' => true,
    ]) ?>

    <?= $form->field($model, 'time_zone')->dropDownList($timezones, [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select time zone ...)'),
    ]) ?>

    <?= $form->field($model, 'datetime_format')->dropDownList([
        'yyyy-MM-dd HH:mm:ss'=> Yii::$app->formatter->asDatetime(time(), 'yyyy-MM-dd HH:mm:ss'),
        'MM/dd/yyyy HH:mm:ss'=> Yii::$app->formatter->asDatetime(time(), 'MM/dd/yyyy HH:mm:ss'),
        'dd/MM/yyyy HH:mm:ss'=> Yii::$app->formatter->asDatetime(time(), 'dd/MM/yyyy HH:mm:ss'),
        'full' => Yii::$app->formatter->asDatetime(time(), 'full'),///'Thursday, March 31, 2016 at 3:30:03 PM Pacific Daylight Time (According to local)',  ///?????i18n:According to local
        'long' => Yii::$app->formatter->asDatetime(time(), 'long'),///'March 31, 2016 at 3:30:03 PM PDT (According to local)',
        'medium' => Yii::$app->formatter->asDatetime(time(), 'medium'),///'Mar 31, 2016, 3:30:03 PM (According to local)',
        'short' => Yii::$app->formatter->asDatetime(time(), 'short'),///'3/31/16, 3:30 PM (According to local)',
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select datetime format ...)'),
    ]) ?>

    <?= $form->field($model, 'date_format')->dropDownList([
        'yyyy-MM-dd'=> Yii::$app->formatter->asDate(time(), 'yyyy-MM-dd'),
        'MM/dd/yyyy'=> Yii::$app->formatter->asDate(time(), 'MM/dd/yyyy'),
        'dd/MM/yyyy'=> Yii::$app->formatter->asDate(time(), 'dd/MM/yyyy'),
        'full' => Yii::$app->formatter->asDate(time(), 'full'),///'Thursday, March 31, 2016 (According to local)',
        'long' => Yii::$app->formatter->asDate(time(), 'long'),///'March 31, 2016 (According to local)',
        'medium' => Yii::$app->formatter->asDate(time(), 'medium'),///'Mar 31, 2016 (According to local)',
        'short' => Yii::$app->formatter->asDate(time(), 'short'),///'3/31/16',
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select date format ...)'),
    ]) ?>

    <?= $form->field($model, 'time_format')->dropDownList([
        'HH:mm:ss'=> Yii::$app->formatter->asTime(time(), 'HH:mm:ss'),
        'full' => Yii::$app->formatter->asTime(time(), 'full'),///'3:30:03 PM Pacific Daylight Time (According to local)',
        'long' => Yii::$app->formatter->asTime(time(), 'long'),///'3:30:03 PM PDT (According to local)',
        'medium' => Yii::$app->formatter->asTime(time(), 'medium'),///'3:30:03 PM (According to local)',
        'short' => Yii::$app->formatter->asTime(time(), 'short'),///'3:30 PM (According to local)',
    ], [
        // 'class' => 'selectpicker',
        'prompt' => Module::t('message', '(Select time format ...)'),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('message', 'Create') : Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
