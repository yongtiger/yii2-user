<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Verify */

$this->title = 'Create Verify';
$this->params['breadcrumbs'][] = ['label' => 'Verifies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
