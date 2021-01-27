/**
 *    会员管理
 */
layui.use(['func', 'form'], function () {
    var form = layui.form,
        func = layui.func,
        $ = layui.$;

    if (A == 'index') {

        //【TABLE列数组】
        var cols = [
            {type: 'checkbox', fixed: 'left'}
            , {field: 'id', width: 80, title: 'ID', align: 'center', sort: true, fixed: 'left'}
            , {
                field: 'avatar_url', width: 120, title: '头像', align: 'center', templet: function (d) {
                    return '<a href="' + d.avatar_url + '" target="_blank"><img src="' + d.avatar_url + '" height="26" /></a>';
                }
            }
            , {field: 'nickname', width: 150, title: '昵称', align: 'center'}
            , {field: 'mobile', width: 130, title: '手机号码', align: 'center'}
            , {
                field: 'user_status', width: 100, title: '状态', align: 'center', templet: function (d) {
                    var str = "";
                    if (d.status == 1) {
                        str = '<span class="layui-btn layui-btn-normal layui-btn-xs">' + d.user_status +'</span>';
                    } else if (d.status == 2) {
                        str = '<span class="layui-btn layui-btn-danger layui-btn-xs">' +d.user_status +'</span>';
                    }
                    return str;
                }
            }
            , {
                field: 'source', width: 100, title: '注册涞源', align: 'center', templet: function (d) {
                    var str = "";
                    if (d.source == 1) {
                        str = '<span class="layui-btn layui-btn-normal layui-btn-xs">微信小程序</span>';
                    } else if (d.source == 2) {
                        str = '<span class="layui-btn layui-btn-danger layui-btn-xs">后台添加</span>';
                    }
                    return str;
                }
            }
            , {field: 'format_last_login_time', width: 180, title: '最后登陆时间', align: 'center'}
            , {field: 'login_count', width: 180, title: '登陆次数', align: 'center'}
            , {field: 'format_add_time', width: 180, title: '注册时间', align: 'center'}
            , {fixed: 'right', width: 300, title: '功能操作区', align: 'center', toolbar: '#toolBar'}
        ];

        //【TABLE渲染】
        func.tableIns(cols, "tableList");

        //【设置弹窗】
        func.setWin("用户", 800, 450);

        //【设置会员状态】
        form.on('switch(status)', function (obj) {
            var status = this.checked ? '1' : '2';

            //发起POST请求
            var url = cUrl + "/setStatus";
            func.ajaxPost(url, {"id": this.value, "status": status}, function (data, res) {
                console.log("请求回调");
            });
        });

        $('#export').click(function () {
            $.ajax({
                url: "/UserDrawn/export",
                dataType: "json",
                type: "GET",
                // data: {"id":123},
                beforeSend: function () {
                    layer.msg('正在导出。。。', {
                        icon: 16
                        , shade: 0.01
                        , time: 0
                    });
                },
                success: function (res) {
                    console.log(res);
                    layer.closeAll();
                    if (res.success) {
                        var $a = $("<a>");
                        $a.attr("href", res.data.file);
                        $a.attr("download", res.data.filename);
                        $("body").append($a);
                        $a[0].click();
                        $a.remove();
                        //2秒后关闭
                        // layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        //     window.location.reload();
                        // });
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                    /*$('#' + form).removeAttr("disabled");
                    layer.closeAll();
                    if (res.success) {
                        //2秒后关闭
                        layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }*/
                },
                error: function () {
                    layer.msg("AJAX请求异常");
                    // if (callback) {
                    //     callback(false);
                    // }
                }
            });
        })
    }
});