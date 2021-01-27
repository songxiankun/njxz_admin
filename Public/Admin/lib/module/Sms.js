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
            , {field: 'mobile', width: 150, title: '手机号', align: 'center'}
            , {field: 'content', width: 480, title: '短信内容', align: 'center'}
            , {field: 'status', width: 90, title: '状态', align: 'center',templet:function (d) {
                    var str = "";
                    if (d.status == 1){
                        str = '发送成功';
                    } else if (d.status == 2) {
                        str = '发送失败';
                    } else if (d.status == 3) {
                        str = '待发送';
                    }
                    return str;
                }}
            , {field: 'add_time', width: 180, title: '发送时间', align: 'center'}
        ];

        //【TABLE渲染】
        func.tableIns(cols, "tableList", function (layEvent, data) {
            if (layEvent == 'sendSms') {
                var url = cUrl + "/sendSms";
                func.showWin("发送短信", url);
            }
        });

        //【设置弹窗】
        func.setWin("短信");
    }
});

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return (false);
}