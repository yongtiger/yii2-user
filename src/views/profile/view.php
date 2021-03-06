<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yongtiger\user\Module;
use yongtiger\region\widgets\RegionWidget;
use yongtiger\region\models\Region;

/* @var $this yii\web\View */
/* @var $model yongtiger\user\models\Profile */

$this->title = Module::t('message', 'View User Profile') . ': ID ' . $model->user_id;

///[v0.18.5 (isAdminEnd)]
if (!empty(Yii::$app->isAdminEnd)) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User Profile List'), 'url' => ['index']];
} else if (Yii::$app->user->id == $model->user_id) {
    $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
}

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="profile-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--///[v0.18.5 (isAdminEnd)]///?????access rules-->
    <?php if (Yii::$app->user->id == $model->user_id): ?>
    <p>
        <?= Html::a(Module::t('message', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'fullname',
            'firstname',
            'lastname',

            [
                'attribute' => 'gender',
                'value' => function ($model) {
                    if ($model->gender === 0) {
                        return Module::t('message', 'Female');
                    } else if ($model->gender === 1) {
                        return Module::t('message', 'Male');
                    }
                    return null;
                },
            ],

            'link:url',

            'birthday:date',    ///[v0.17.2 (profile birthday:DatePicker)]

            // 'country',   ///?????need upgrade region

            ///[v0.17.3 (profile region widget)]
            [
                'label' => Module::t('message', 'Region'),
                'value' => Region::createRegion($model->province, $model->city, $model->district),            
            ],

            'address',
            'telephone',
            'mobile',
            'graduate',

            [
                'attribute' => 'education',
                'value' => function ($model) {
                    return Module::t('message', $model->education);
                },
            ],

            'company',
            [
                'attribute' => 'position',
                'value' => function ($model) {
                    return Module::t('message', $model->position);
                },
            ],

            [
                'attribute' => 'revenue',
                'value' => function ($model) {
                    return Module::t('message', $model->revenue);
                },
            ],
            
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
