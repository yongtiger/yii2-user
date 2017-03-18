<?php ///[Yii2 uesr:preference]

use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Preference */

$this->title = Module::t('message', 'View User Preference') . ': ID ' . $model->user_id;

///[v0.18.5 (isAdminEnd)]
if (!empty(Yii::$app->isAdminEnd)) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Preference List'), 'url' => ['index']];
} else if (Yii::$app->user->id == $model->user_id) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
}

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="preference-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--///[v0.18.5 (isAdminEnd)]-->
    <?php if (!empty(Yii::$app->isAdminEnd)): ?>
    <p>
        <?= Html::a(Module::t('message', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-success']) ?>
    </p>
    <? endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'locale',
            'time_zone',
            'datetime_format',
            'date_format',
            'time_format',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
