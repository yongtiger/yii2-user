<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\User */

$this->title = Module::t('message', 'View User') . ': ID ' . $model->id;

///[v0.18.5 (isAdminEnd)]
if (Yii::$app->isAdminEnd) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
} else if (Yii::$app->user->id == $model->id) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
}

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--///[v0.18.5 (isAdminEnd)]-->
    <?php if (Yii::$app->isAdminEnd): ?>
    <p>
        <?= Html::a(Module::t('message', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('message', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('message', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <? endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'status', 
                'value' => function($model) {   ///[yii2-user v0.12.1 (DetailView value)]
                    $arrStatus = [User::STATUS_INACTIVE => 'inactive', User::STATUS_ACTIVE => 'active'];
                    return Module::t('message', $arrStatus[$model->status]);
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
