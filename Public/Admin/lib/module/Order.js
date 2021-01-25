/**
 * @brief 设备管理
 */
layui.use(['func'], function () {
    var func = layui.func,
        laydate = layui.laydate,
        $ = layui.$;
    if (A == 'index') {
        //【TABLE列数组】
        var cols = [
            {type: 'checkbox', fixed: 'left'}
            , {field: 'id', width: 80, title: 'ID', align: 'center', sort: true, fixed: 'left'}
            , {field: 'admin_name', width: 120, title: '申请人', align: 'center'}
            , {field: 'user_name', width: 120, title: '维修人工号', align: 'center'}
            , {field: 'format_order_time', width: 200, title: '订单生成时间', align: 'center'}
            , {field: 'format_receive_time', width: 200, title: '订单接单时间', align: 'center'}
            , {field: 'format_end_time', width: 200, title: '订单完成时间', align: 'center'}
            , {field: 'sign_ok', width: 200, title: '是否签字', align: 'center'}
            , {field: 'sign_name', width: 200, title: '签字人', align: 'center'}
            , {field: 'format_sign_time', width: 200, title: '签字时间', align: 'center'}
            , {field: 'status',width: 100,title: '维修状态', align: 'center',templet: '#statusTpl',
                templet: function (d) {
                    var str = "";
                    if (d.status == 3) {
                        str = '<span class="layui-btn layui-btn-normal layui-btn-xs">维修结束</span>';
                    } else if(d.status == 1) {
                        str = '<span class="layui-btn layui-btn-normal layui-btn-xs layui-btn-danger">待维修</span>';
                    } else if (d.status == 2)
                    {
                        str = '<span class="layui-btn-warm layui-btn layui-btn-normal layui-btn-xs " > 维修中</span>'
                    }
                    return str;
                }
            }
            , {field: 'note', width: 200, title: '维修备注', align: 'center'}
            , {field: 'format_add_time', width: 180, title: '添加时间', align: 'center', sort: true}
            , {field: 'format_upd_time', width: 180, title: '更新时间', align: 'center', sort: true}
            , {fixed: 'right', width: 200, title: '功能操作区', align: 'center', toolbar: '#toolBar'}
        ];

        //【TABLE渲染】
        func.tableIns(cols, "tableList", function (layEvent, data) {
        },'', '','','','维续账单流水');

        func.setWin("维修账单");
    } else {
        //入职日期
        func.initDate(['achieve_time|datetime'], function (value, date) {
            console.log("当前选择日期:" + value);
            console.log("日期详细信息：" + JSON.stringify(date));
        });
    }
    $("#")
});