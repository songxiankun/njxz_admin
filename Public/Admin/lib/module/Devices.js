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
            , {field: 'num', width: 120, title: '资产编号', align: 'center'}
            , {field: 'device_name', width: 150, title: '资产名称', align: 'center'}
            , {field: 'type_name', width: 200, title: '型号', align: 'center'}
            , {field: 'norm', width: 120, title: '规格', align: 'center'}
            , {field: 'count', width: 100, title: '数量', align: 'center'}
            , {field: 'money', width: 200, title: '价值(元)', align: 'center'}
            , {field: 'format_achieve_time', width: 180, title: '交付日期', align: 'center'}
            , {field: 'department_name', width: 100, title: '使用部门', align: 'center'}
            , {field: 'admin_name', width: 100, title: '管理人员', align: 'center'}
            , {field: 'address', width: 100, title: '所属机房', align: 'center'}
            , {field: 'format_add_user', width: 150, title: '创建人', align: 'center'}
            , {field: 'format_add_time', width: 180, title: '添加时间', align: 'center', sort: true}
            , {field: 'format_upd_time', width: 180, title: '更新时间', align: 'center', sort: true}
            , {fixed: 'right', width: 200, title: '功能操作区', align: 'center', toolbar: '#toolBar'}
        ];

        //【TABLE渲染】
        func.tableIns(cols, "tableList", function (layEvent, data) {
        },'', '','','','设备管理表');

        func.setWin("设备信息");
    } else {
        //入职日期
        func.initDate(['achieve_time|datetime'], function (value, date) {
            console.log("当前选择日期:" + value);
            console.log("日期详细信息：" + JSON.stringify(date));
        });
    }
    $("#")
});