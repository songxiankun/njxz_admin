// /**
//  * 机房实验室管理
//  */
layui.use(['func'],function(){
    //【声明变量】
    let func = layui.func
        ,$ = layui.$;

    if(A == 'index') {

        //【TABLE列数组】
        let cols = [
             { type:'checkbox', fixed: 'left' }
            ,{ field:'id', width:80, title: 'ID', align:'center', sort: true, fixed: 'left' }
            ,{ field:'name', width:300, title: '机房名称', align:'center' }
            ,{ field:'num', width:120, title: '机房编号', align:'center' }
            ,{ field:'building_name', width:100, title: '所属楼名', align:'center' }
            ,{ field:'floor', width:150, title: '所在楼层', align:'center' }
            ,{ field:'format_admin_name', width:350, title: '机房负责人', align:'center' }
            ,{ field:'format_add_user', width:100, title: '创建人', align:'center' }
            ,{ field:'format_add_time', width:180, title: '添加时间', align:'center', sort: true }
            ,{ field:'format_upd_time', width:180, title: '更新时间', align:'center', sort: true }
            ,{ field:'note', width:200, title: '备注', align:'center' }
            ,{ fixed:'right', width:280, title: '功能操作区', align:'center', toolbar: '#toolBar' }
        ];

        //【渲染TABLE】
        func.tableIns(cols,"tableList");
        //【设置弹框】
        func.setWin("实验室");
    }
});