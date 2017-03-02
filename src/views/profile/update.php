<?php

use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Profile */

$this->title = Module::t('message', 'Update User Profile') . ': ID ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Profile List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'ID ' . $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Module::t('message', 'Update');
?>
<div class="profile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
