<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yongtiger\user\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

///[yii2-admin-boot_v0.4.2_f0.4.1_user_deleteIn]
$this->registerJs('
    $(".gridview").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);

        ///[yii2-admin-boot_v0.5.9_f0.5.7_user_login_popup]
        ///if(confirm("您确定要删除 ,这是不可恢复操作")){
        ///    $.post("'.Url::to(['delete-in']).'","selected="+keys).error(function(xhr,errorText,errorType){  ///添加Ajax错误处理，解决批量删除出错时无任何显示！
        ///        if(xhr.status!=302) ///忽略302页面跳转错误！
        ///            alert(xhr.responseText)
        ///    });
        ///}

        yii.confirm("您确定要删除 ,这是不可恢复操作",
            function () {
                $.post("'.Url::to(['delete-in']).'","selected="+keys).error(function(xhr,errorText,errorType){  ///添加Ajax错误处理，解决批量删除出错时无任何显示！
                    if(xhr.status!=302) ///忽略302页面跳转错误！
                        alert(xhr.responseText)
                });
            }
        );

    });
');

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    ///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]
    echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-success gridview']) ?><!--///[yii2-admin-boot_v0.4.2_f0.4.1_user_deleteIn]-->
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'], ///[yii2-admin-boot_v0.4.2_f0.4.1_user_deleteIn]
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn', 'name' => 'id'], ///[yii2-admin-boot_v0.4.2_f0.4.1_user_deleteIn]

            'id',
            'username',
            'email:email',

            ///[yii2-admin-boot_v0.5.14_f0.5.13_user_dropdownlist_role]
            ///'role', ///[yii2-admin-boot_v0.5.1_f0.5.0_user_add_role_field]
            [
                'attribute' => 'role',
                'filter' => [User::ROLE_ADMIN => 'ROLE_ADMIN', User::ROLE_SUPER_MODERATOR => 'ROLE_SUPER_MODERATOR', User::ROLE_MODERATOR => 'ROLE_ADMIN', User::ROLE_USER => 'ROLE_USER'],
                ///'filter' => Html::activeDropDownList($searchModel, 'role', [User::ROLE_ADMIN => 'ROLE_ADMIN', User::ROLE_SUPER_MODERATOR => 'ROLE_SUPER_MODERATOR', User::ROLE_MODERATOR => 'ROLE_ADMIN', User::ROLE_USER => 'ROLE_USER'], ['prompt' => '', 'class' => 'form-control']),    ///与上面相同！
            ],
            ///[http://www.brainbook.cc]

            ///[yii2-admin-boot_v0.5.13_f0.5.12_user_dropdownlist_status]
            [
                'attribute' => 'status',
                'filter' => [User::STATUS_INACTIVE => 'STATUS_INACTIVE', User::STATUS_ACTIVE => 'STATUS_ACTIVE'],
                ///'filter' => Html::activeDropDownList($searchModel, 'status', [User::STATUS_INACTIVE => 'STATUS_INACTIVE', User::STATUS_ACTIVE => 'STATUS_ACTIVE'], ['prompt' => '', 'class' => 'form-control']),    ///与上面相同！
            ],
            ///[http://www.brainbook.cc]

            ///[yii2-admin-boot_v0.5.15_f0.5.14_user_datepicker]
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:Y-m-d H:i:s'],
                'filter' => DatePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'created_at', 
                        'dateFormat' => 'yyyy-MM-dd', 
                        'options' => [
                            'id' => 'datepicker_created_at',    ///注意：没有id，出不来DatePicker！！！
                            'style' => 'text-align: center', 
                            'class' => 'form-control'   ///样式与表单统一
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
                            'id' => 'datepicker_updated_at',    ///注意：没有id，出不来DatePicker！！！
                            'style' => 'text-align: center', 
                            'class' => 'form-control'   ///样式与表单统一
                        ]
                    ]
                )
            ],
            ///[http://www.brainbook.cc]

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
