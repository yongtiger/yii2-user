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
$this->registerJs(
<<<JS
$(".gridview").on("click", function () {
    //Note: `$("#grid")` must match the `options id` of our first step!
    var keys = $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);

    ///[yii2-admin-boot_v0.5.9_f0.5.7_user_login_popup]     ///??????
    ///if(confirm("Are you sure you want to delete? This is a non-recoverable operation!")){
    ///    $.post("'.Url::to(['delete-in']).'","selected="+keys).error(function(xhr,errorText,errorType){  ///Add Ajax error handling, solve batch delete error without any display!
    ///        if(xhr.status!=302) ///ignore #302 page jump error
    ///            alert(xhr.responseText)
    ///    });
    ///}

    yii.confirm("Are you sure you want to delete? This is a non-recoverable operation!",    ///??????i18n
        function () {
            $.post("'.Url::to(['delete-in']).'","selected="+keys).error(function(xhr,errorText,errorType){  ///Add Ajax error handling, solve batch delete error without any display!
                if(xhr.status!=302) ///ignore #302 page jump error
                    alert(xhr.responseText)
            });
        }
    );

});
JS
);

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Module::t('user', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('user', 'Batch Delete'), "javascript:void(0);", ['class' => 'btn btn-success gridview']) ?><!--///[yii2-user:deleteIn]-->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'], ///[yii2-user:deleteIn]
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn', 'name' => 'id'], ///[yii2-user:deleteIn]

            'id',
            'username',
            'email:email',

            [
                'attribute' => 'role',
                'filter' => [User::ROLE_ADMIN => Module::t('user', 'role_admin'), User::ROLE_SUPER_MODERATOR => Module::t('user', 'role_super_moderator'), User::ROLE_MODERATOR => Module::t('user', 'role_moderator'), User::ROLE_USER => Module::t('user', 'role_user')],
            ],

            [
                'attribute' => 'status',
                'filter' => [User::STATUS_INACTIVE => Module::t('user', 'inactive'), User::STATUS_ACTIVE => Module::t('user', 'active')],
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
</div>
