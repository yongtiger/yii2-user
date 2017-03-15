<?php ///[Yii2 uesr:count]

use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Count */

$this->title = Module::t('message', 'Update User Count') . ': ID ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Count List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'ID ' . $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="count-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
