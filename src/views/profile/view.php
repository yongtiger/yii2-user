<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;
use yongtiger\region\widgets\RegionWidget;
use yongtiger\region\models\Region;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Profile */

$this->title = Module::t('message', 'View User Profile') . ': ID ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Profile'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="profile-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('message', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fullname',
            'firstname',
            'lastname',
            'gender',
            'language',
            'avatar',
            'link',
            'birthday:date',    ///[v0.17.2 (profile birthday:DatePicker)]
            'country',

            ///[v0.17.3 (profile region widget)]
            [
                'label' => Module::t('message', 'Region'),
                'value' => Region::createRegion($model->province, $model->city, $model->district),            
            ],

            'address',
            'telephone',
            'mobile',
            'graduate',
            'education',
            'company',
            'position',
            'revenue',
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
            ['attribute' => 'updated_at', 'format' => ['datetime', 'php:Y-m-d H:i:s']],
        ],
    ]) ?>

</div>
