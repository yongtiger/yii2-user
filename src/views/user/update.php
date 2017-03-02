<?php

use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\User */

$this->title = Module::t('message', 'Update User') . ': ID ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'ID ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('message', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
