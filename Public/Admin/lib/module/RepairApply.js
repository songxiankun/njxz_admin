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
            , {field: 'organized_name', width: 120, title: '学校名称', align: 'center', sort: true, fixed: 'left'}
            , {field: 'department_name', width: 120, title: '所属部门', align: 'center', sort: true, fixed: 'left'}
            , {field: 'building_name', width: 120, title: '所属楼', align: 'center', sort: true, fixed: 'left'}
            , {field: 'engin_room', width: 120, title: '所属机房', align: 'center', sort: true, fixed: 'left'}
            , {field: 'device_id', width: 120, title: '设备编号', align: 'center', sort: true, fixed: 'left'}
            , {field: 'admin_name', width: 120, title: '申请人', align: 'center'}
            , {field: 'device_detile', width: 200, title: '设备维修详情', align: 'center'}
            , {field: 'images', width: 200, title: '设备维修验证图片', align: 'center'}
            , {field: 'videos', width: 200, title: '设备维修验证视频', align: 'center'}
            , {field: 'admin_user_name', width: 120, title: '审核人姓名', align: 'center'}
            , {
                field: 'status', width: 100, title: '申请状态', align: 'center', templet: '#statusTpl',
                templet: function (d) {
                    var str = "";
                    if (d.status == 3) {
                        str = '<span class="layui-btn layui-btn-normal layui-btn-xs">审核通过</span>';
                    } else if (d.status == 1) {
                        str = '<span class="layui-btn layui-btn-normal layui-btn-xs layui-btn-danger">待审核</span>';
                    } else if (d.status == 2) {
                        str = '<span class="layui-btn-warm layui-btn layui-btn-normal layui-btn-xs " >审核未通过</span>'
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
        }, '', '', '', '', '维修申请管理');

        func.setWin("维修申请");
    } else {
        //入职日期
        func.initDate(['achieve_time|datetime'], function (value, date) {
            console.log("当前选择日期:" + value);
            console.log("日期详细信息：" + JSON.stringify(date));
        });
    }
    $("#")
});