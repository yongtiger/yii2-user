<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $searchModel yongtiger\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('user', 'User List');
$this->params['breadcrumbs'][] = $this->title;

///[yii2-user:deleteIn]
$this->registerJs('
    $(".gridview").on("click", function () {
        //Note: `$("#grid")` must match the `options id` of our first step!
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);

        ///[yii2-admin-boot_v0.5.9_f0.5.7_user_login_popup]     ///??????
        ///if(confirm("' . Module::t('user', 'Are you sure you want to delete? This is a non-recoverable operation!') . '")){
        ///    $.post("' . Url::to(['delete-in']) . '","selected="+keys).error(function(xhr,errorText,errorType){  ///Add Ajax error handling, solve batch delete error without any display!
        ///        if(xhr.status!=302) ///ignore #302 page jump error
        ///            alert(xhr.responseText)
        ///    });
        ///}

        yii.confirm("' . Module::t('user', 'Are you sure you want to delete? This is a non-recoverable operation!') . '",
            function () {
                $.post("' . Url::to(['delete-in']) . '","selected="+keys).error(function(xhr,errorText,errorType){  ///Add Ajax error handling, solve batch delete error without any display!
                    if(xhr.status!=302) ///ignore #302 page jump error
                        alert(xhr.responseText)
                });
            }
        );

    });
');

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('user', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('user', 'Batch Delete'), "javascript:void(0);", ['class' => 'btn btn-danger gridview']) ?><!--///[yii2-user:deleteIn]-->
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
                'attribute' => 'role',
                'filter' => [User::ROLE_ADMIN => Module::t('user', 'role_admin'), User::ROLE_SUPER_MODERATOR => Module::t('user', 'role_super_moderator'), User::ROLE_MODERATOR => Module::t('user', 'role_moderator'), User::ROLE_USER => Module::t('user', 'role_user')],
                'value' => function($model) {   ///[yii2-user v0.11.1 (GridView value)]
                    return Module::t('user', $model->role);
                }
            ],

            [
                'attribute' => 'status',
                'filter' => [User::STATUS_INACTIVE => Module::t('user', 'inactive'), User::STATUS_ACTIVE => Module::t('user', 'active')],
                'value' => function($model) {   ///[yii2-user v0.11.1 (GridView value)]
                    $arrStatus = [User::STATUS_INACTIVE => 'inactive', User::STATUS_ACTIVE => 'active'];
                    return Module::t('user', $arrStatus[$model->status]);
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <hr style="height:10px">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

</div>
