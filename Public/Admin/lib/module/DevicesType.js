/**
 *    设备类型管理
 */
layui.use(['func', 'common'], function () {
    var func = layui.func,
        common = layui.common,
        $ = layui.$;

    if (A == 'index') {
        //【TREE列数组
        var layout =
            [{
                name: 'ID',
                headerClass: 'value_col',
                colClass: 'value_col',
                style: 'width: 5%',
                render: function (row) {
                    return row.id;
                }
            },
                {
                    name: '设备类型名称',
                    treeNodes: true,
                    headerClass: 'value_col2',
                    colClass: 'value_col2',
                    style: '15%;min-width:200px;',
                    render: function (row) {
                        return row.name;
                    }
                },
                {
                    name: '设备类型备注',
                    headerClass: 'value_col3',
                    colClass: 'value_col3',
                    style: '30%;min-width:200px;',
                    render: function (row) {
                        if (row.note == null) {
                            return "暂无备注";
                        }
                        return row.note;
                    }
                },
                {
                    name: '操作',
                    headerClass: 'value_col',
                    colClass: 'value_col2',
                    style: 'width: 20%;min-width:180px;',
                    render: function (row) {
                        var strXml = $("#toolBar").html();
                        var regExp = /<a.*?>([\s\S]*?)<\/a>/g;
                        var itemArr = strXml.match(regExp);
                        if (itemArr) {
                            var itemStr = "";
                            for (var i = 0; i < itemArr.length; i++) {
                                if (i == 2 && row.level > 2) continue;
                                itemStr += itemArr[i].replace('<a', "<a data-id=" + row.id);
                            }
                            return itemStr;
                        }
                        return "";
                    }
                }];
        //【TREE渲染】
        func.treeIns(layout, "treeList");

        //【设置弹框】
        func.setWin("设备类型", 500, 400);
    }
});
