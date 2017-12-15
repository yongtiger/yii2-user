<?php
///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]
use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Preference */

$this->title = Module::t('message', 'Create User Preference');

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="preference-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
