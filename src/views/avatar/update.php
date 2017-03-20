<?php ///[v0.21.0 (ADD# update avatar)]

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yongtiger\user\Module;
use yongtiger\cropperavatar\AvatarWidget;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Profile */

$this->title = Module::t('message', 'Update User Avatar');
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="avatar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'avatar-form']); ?>

    <?= $form->field($model, 'avatar')->widget(AvatarWidget::classname(), [
        'dstImageUri' => Yii::$app->user->isGuest ? '@web/uploads/avatar/0' : '@web/uploads/avatar/' . Yii::$app->user->identity->id,
        // 'noImageUrl' => 'http://oxfordchamber.org/images/board/NoPhotoAvailableMale.jpg',
        // 'isRounded' => true,
    	'isModal' => false,
    	// 'enableRotateButtons' => false,
    	// 'enablePreviewLargelImage' => false,
    	// 'enablePreviewMiddlelImage' => false,
    	// 'enablePreviewSmalllImage' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('message', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
