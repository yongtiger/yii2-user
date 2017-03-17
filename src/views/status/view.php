<?php ///[Yii2 uesr:status]

use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Status */

$this->title = Module::t('message', 'View User Status') . ': ID ' . $model->user_id;

///[v0.18.5 (isAdminEnd)]
if (Yii::$app->isAdminEnd) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Status List'), 'url' => ['index']];
} else if (Yii::$app->user->id == $model->user_id) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
}

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="status-view">

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
            'registration_ip',
            'last_login_ip',
            'last_login_at:datetime',
            'banned_at:datetime',
            'banned_reason',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
