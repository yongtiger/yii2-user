<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yongtiger\user\models\User;
use yongtiger\user\Module;
use yongtiger\user\UserAsset;

/* @var $this yii\web\View */
/* @var $searchModel yongtiger\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('message', 'User List');
$this->params['breadcrumbs'][] = $this->title;

///[yii2-user:deleteIn]
$this->registerJs('
var delete_in_url = "' . Url::to(['delete-in']) . '";
var delete_in_msg = "' . Module::t('message', 'Are you sure you want to delete? This is a non-recoverable operation!') . '";
', View::POS_HEAD);
UserAsset::register($this);

?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('message', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('message', 'Batch Delete'), "javascript:void(0);", ['class' => 'btn btn-danger gridview']) ?><!--///[yii2-user:deleteIn]-->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'], ///[yii2-user]@see http://stackoverflow.com/questions/29837479/yii2-add-horizontal-scrollbar-in-gridview
        
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn', 'name' => 'id'], ///[yii2-user:deleteIn]

            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '60']    ///[yii2-user v0.11.3 (GridView columns headerOptions)]
            ],

            'username',
            'email:email',

            [
                'attribute' => 'status',
                'filter' => [User::STATUS_INACTIVE => Module::t('message', 'inactive'), User::STATUS_ACTIVE => Module::t('message', 'active')],
                'value' => function($model) {   ///[yii2-user v0.11.1 (GridView value)]
                    $arrStatus = [User::STATUS_INACTIVE => 'inactive', User::STATUS_ACTIVE => 'active'];
                    return Module::t('message', $arrStatus[$model->status]);
                },
            ],

            ///[yii2-user:datepicker]
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:Y-m-d H:i:s'],
                'filter' => DatePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'created_at', 
                        'dateFormat' => 'yyyy-MM-dd', 
                        'options' => [
                            'id' => 'datepicker_created_at',    ///Note: if no `id`, `DatePicker` dosen't work!
                            'style' => 'text-align: center', 
                            'class' => 'form-control'   ///The style is consistent with the form
                        ]
                    ]
                )
            ],

            ['attribute' => 'updated_at', 'format' => ['datetime', 'php:Y-m-d H:i:s'],
                'filter' => DatePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'updated_at', 
                        'dateFormat' => 'yyyy-MM-dd', 
                        'options' => [
                            'id' => 'datepicker_updated_at',    ///Note: if no `id`, `DatePicker` dosen't work!
                            'style' => 'text-align: center', 
                            'class' => 'form-control'   ///The style is consistent with the form
                        ]
                    ]
                )
            ],
            ///[http://www.brainbook.cc]

            ///[yii2-user v0.14.0 (user index:ActionColumn)]@see http://www.yiiframework.com/forum/index.php/topic/49595-how-to-change-buttons-in-actioncolumn/
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {profile} {verify}',
                'buttons' => [
                    'profile' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-info-sign"></span>',
                            $url,
                            ['title' => Module::t('message', 'profile')]
                        );
                    },
                    'verify' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-check"></span>',
                            $url,
                            ['title' => Module::t('message', 'verify')]
                        );
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'profile') {
                        $url = ['profile/update', 'id' => $key];
                        return $url;
                    } else if ($action === 'verify') {
                        $url = ['verify/update', 'id' => $key];
                        return $url;
                    } else {    ///@see http://stackoverflow.com/questions/29642962/actioncolumns-button-with-custom-url
                        return [$action, 'id' => $model->id];
                    }
                }
            ],
        ],
    ]); ?>

    <hr style="height:10px">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

</div>
