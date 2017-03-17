<?php ///[Yii2 uesr:count]

use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Count */

$this->title = Module::t('message', 'View User Count') . ': ID ' . $model->user_id;

///[v0.18.5 (isAdminEnd)]
if (Yii::$app->isAdminEnd) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Count List'), 'url' => ['index']];
} else if (Yii::$app->user->id == $model->user_id) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
}

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="count-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--///[v0.18.5 (isAdminEnd)]-->
    <?php if (Yii::$app->isAdminEnd): ?>
    <p>
        <?= Html::a(Module::t('message', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-success']) ?>
    </p>
    <? endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'login_count',
            'banned_count',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
