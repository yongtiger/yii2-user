<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\User */

$this->title = 'ID ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'User List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('user', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('user', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'status',
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
            ['attribute' => 'updated_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
        ],
    ]) ?>

</div>
