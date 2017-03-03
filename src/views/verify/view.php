<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Verify */

$this->title = Module::t('message', 'View User Verify') . ': ID ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Verify'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--///[v0.17.0 (AccessControl of update profile and remove update verify)]-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'password_verified_at:datetime',
            'email_verified_at:datetime',
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
            ['attribute' => 'updated_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
        ],
    ]) ?>

</div>
