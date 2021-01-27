/**
 * @brief 楼区管理
 */
layui.use(['func'], function () {
    var func = layui.func,
        $ = layui.$;
    if (A == 'index') {
        //【TABLE列数组】
        var cols = [
            {type: 'checkbox', fixed: 'left'}
            , {field: 'id', width: 80, title: 'ID', align: 'center', sort: true, fixed: 'left'}
            , {field: 'name', width: 200, title: '楼区名称', align: 'center'}
            , {field: 'floors', width: 180, title: '楼层数', align: 'center'}
            , {field: 'note', width: 400, title: '备注', align: 'left'}
            , {fixed: 'right', width: 300, title: '功能操作区', align: 'center', toolbar: '#toolBar'}
        ];

        //【TABLE渲染】
        func.tableIns(cols,"tableList");

        func.setWin("楼区信息");
    }
});