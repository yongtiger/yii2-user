<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $searchModel yongtiger\user\models\VerifySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('message', 'User Verify List');
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'User List'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="verify-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'], ///[yii2-user]@see http://stackoverflow.com/questions/29837479/yii2-add-horizontal-scrollbar-in-gridview

        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'headerOptions' => ['width' => '60']    ///[yii2-user v0.11.3 (GridView columns headerOptions)]
            ],

            ///[yii2-user:datepicker]
            ['attribute' => 'password_verified_at', 'format' => ['datetime', 'php:Y-m-d H:i:s'],
                'filter' => DatePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'password_verified_at', 
                        'dateFormat' => 'yyyy-MM-dd', 
                        'options' => [
                            'id' => 'datepicker_password_verified_at',    ///Note: if no `id`, `DatePicker` dosen't work!
                            'style' => 'text-align: center', 
                            'class' => 'form-control'   ///The style is consistent with the form
                        ]
                    ]
                )
            ],

            ['attribute' => 'email_verified_at', 'format' => ['datetime', 'php:Y-m-d H:i:s'],
                'filter' => DatePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'email_verified_at', 
                        'dateFormat' => 'yyyy-MM-dd', 
                        'options' => [
                            'id' => 'datepicker_email_verified_at',    ///Note: if no `id`, `DatePicker` dosen't work!
                            'style' => 'text-align: center', 
                            'class' => 'form-control'   ///The style is consistent with the form
                        ]
                    ]
                )
            ],

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

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    
    <hr style="height:10px">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

</div>
