<?php

use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\User */

$this->title = Module::t('user', 'Update User') . ': ID ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'User List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'User') . 'ID ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('user', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
