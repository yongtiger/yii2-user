<?php
///[yii2-adminlte-asset_v0.1.0_f0.0.0_left]
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            ///'auth_key',  ///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]
            ///'password_hash', [yii2-admin-boot_v0.5.17_f0.5.16_user_password]
            ///'password_reset_token', ///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]
            'email:email',
            'role', ///[yii2-admin-boot_v0.5.1_f0.5.0_user_add_role_field]
            'status',
            ///[yii2-admin-boot_v0.4.3_f0.4.2_user_datetime]
            ///'created_at:datetime',
            ///'updated_at:datetime',
            ///['attribute' => 'created_at', 'value' => date("Y-m-d H:i:s")],
            ///['attribute' => 'updated_at', 'value' => date("Y-m-d H:i:s")],
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
            ['attribute' => 'updated_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
            ///[http://www.brainbook.cc]
        ],
    ]) ?>

</div>
<!--///[http://www.brainbook.cc]-->