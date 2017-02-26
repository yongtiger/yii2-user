<?php
///[yii2-adminlte-asset_v0.1.0_f0.0.0_left]
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<!--///[http://www.brainbook.cc]-->