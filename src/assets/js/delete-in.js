$(".gridview").on("click", function () {
    //Note: `$("#grid")` must match the `options id` of our first step!
    var keys = $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);

    ///[yii2-admin-boot_v0.5.9_f0.5.7_user_login_popup]     ///??????
    ///if(confirm("' . Module::t('message', 'Are you sure you want to delete? This is a non-recoverable operation!') . '")){
    ///    $.post("' . Url::to(['delete-in']) . '","selected="+keys).error(function(xhr,errorText,errorType){  ///Add Ajax error handling, solve batch delete error without any display!
    ///        if(xhr.status!=302) ///ignore #302 page jump error
    ///            alert(xhr.responseText)
    ///    });
    ///}

    yii.confirm(delete_in_msg,
        function () {
            $.post(delete_in_url,"selected="+keys).error(function(xhr,errorText,errorType){  ///Add Ajax error handling, solve batch delete error without any display!
                if(xhr.status!=302) ///ignore #302 page jump error
                    alert(xhr.responseText)
            });
        }
    );

});