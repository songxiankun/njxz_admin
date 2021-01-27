<?php if (!defined('THINK_PATH')) exit();?><center>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>上传数据文件</legend>
    </fieldset>

    <div class="layui-upload-drag" id="LAY-excel-upload">
        <i class="layui-icon"></i>
        <p>点击上传，或将文件拖拽到此处</p>
    </div>
    <br>
    <br>
    <br>
    <div class="layui-btn layui-btn-radius" id="import"> 点击上传</div>
</center>
<script type="text/javascript">
    layui.config({
        version: Math.random(),
        base: '/Public/Admin'
    }).extend({
        excel: '/js/layui_exts/excel',
    }).use(['jquery', 'layer', 'upload', 'excel', 'laytpl', 'element', 'code'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var upload = layui.upload;
        var excel = layui.excel;
        var laytpl = layui.laytpl;
        var element = layui.element;

        /**
         * 上传excel的处理函数，传入文件对象数组
         * @param  {FileList} files [description]
         * @return {[type]}       [description]
         */
        function uploadExcel(files) {
            try {
                excel.importExcel(files, {}, function (data, book) {
                    /**
                     * 2019-06-21 JeffreyWang 应群友需求，加一个单元格合并还原转换
                     * 思路：
                     * 1. 渲染时为每个cell加上唯一的ID，demo里边采用 table-export-文件索引-sheet名称-行索引-列索引
                     * 2. 根据 book[文件索引].Sheets[sheet名称]['!merge'] 参数，取左上角元素设置 colspan 以及 rowspan，并删除其他元素
                     */
                   layer.open({
                        title: 'excel数据转换结果'
                        , area: ['800px', '600px']
                        , tipsMore: true
                        , content: laytpl($('#LAY-excel-export-ans').html()).render({data: data, files: files})
                        , success: function () {
                            element.render('tab')
                            layui.code({})
                            // 处理合并
                            for (var file_index in book) {
                                if (!book.hasOwnProperty(file_index)) {
                                    continue
                                }
                                // 遍历每个Sheet
                                for (var sheet_name in book[file_index].Sheets) {
                                    if (!book[file_index].Sheets.hasOwnProperty(sheet_name)) {
                                        continue
                                    }
                                    var sheetObj = book[file_index].Sheets[sheet_name]
                                    // 仅在有合并参数时进行操作
                                    if (!sheetObj['!merges']) {
                                        continue
                                    }
                                    // 遍历每个Sheet中每个 !merges
                                    for (var merge_index = 0; merge_index < sheetObj['!merges'].length; merge_index++) {
                                        var mergeObj = sheetObj['!merges'][merge_index]
                                        // 每个合并参数的 s.c 表示左上角单元格的列，s.r 表示左上角单元格的行，e.c 表示右下角单元格的列，e.r 表示右下角单元格的行，计算时注意 + 1
                                        $('#table-export-' + file_index + '-' + sheet_name + '-' + mergeObj.s.r + '-' + mergeObj.s.c)
                                            .prop('rowspan', mergeObj.e.r - mergeObj.s.r + 1)
                                            .prop('colspan', mergeObj.e.c - mergeObj.s.c + 1)
                                        for (var r = mergeObj.s.r; r <= mergeObj.e.r; r++) {
                                            for (var c = mergeObj.s.c; c <= mergeObj.e.c; c++) {
                                                // 排除左上角
                                                if (r === mergeObj.s.r && c === mergeObj.s.c) {
                                                    continue
                                                }
                                                $('#table-export-' + file_index + '-' + sheet_name + '-' + r + '-' + c).remove()
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    })
                })
            } catch (e) {
                layer.alert(e.message)
            }
        }

        //upload上传实例
        upload.render({
            elem: '#LAY-excel-upload'       //  绑定元素
            , url: '/Devices/import'        //  上传接口（PS:这里不用传递整个 excel）
            , auto: false                   //  选择文件后不自动上传
            , accept: 'file'
            , exts: 'xls|xlsx'              //  只允许上传excel文件
            , bindAction: '#import'         //  指向一个按钮触发上传
            , before: function (obj) {      //  obj参数包含的信息，跟 choose回调完全一致，可参见上文
                var loading = layer.load();               //  上传loading
            }
            , choose: function (obj) {      // 选择文件回调
                var files = obj.pushFile()
                var fileArr = Object.values(files)  // 注意这里的数据需要是数组，所以需要转换一下
                uploadExcel(fileArr) // 如果只需要最新选择的文件，可以这样写： uploadExcel([files.pop()])
            }
            , done: function (res) {
                if (res.success === true) {
                    layer.closeAll('loading'); //关闭loading
                    var all_msg = res.msg + "一共：" + res.data.all + "条数据!!" + "导入成功：" + res.data.import + "条数据";
                    layer.msg(all_msg, {
                        icon: 1,        //  笑脸
                        time:2000       // 2秒关闭
                    }, function(){
                        // 获取子窗口信息
                        var index = parent.layer.getFrameIndex(window.name);
                        // 关闭子窗口
                        parent.layer.close(index);
                        // 刷新父页面
                        window.parent.location.reload();
                    });
                }

            }
            , error: function (index, upload) {
                layer.closeAll('loading'); //关闭loading-->
            }
        });

        $(function () {
            // 监听上传文件的事件
            $('#LAY-excel-import-excel').change(function (e) {
                // 注意：这里直接引用 e.target.files 会导致 FileList 对象在读取之前变化，导致无法弹出文件
                var files = Object.values(e.target.files)
                uploadExcel(files)
                // 变更完清空，否则选择同一个文件不触发此事件
                e.target.value = ''
            })
            // 文件拖拽
            document.body.ondragover = function (e) {
                e.preventDefault()
            }
            document.body.ondrop = function (e) {
                e.preventDefault()
                var files = e.dataTransfer.files
                uploadExcel(files)
            }
        })
    })

    /**
     * 上传excel的处理函数，传入文件对象数组
     * @param  {[type]} files [description]
     * @return {[type]}       [description]
     */
    function uploadExcel(files) {
        layui.use(['excel', 'layer'], function () {
            var excel = layui.excel
            var layer = layui.layer
            try {
                excel.importExcel(files, {
                    // 读取数据的同时梳理数据
                    fields: {
                        'id': 'A'
                        , 'username': 'B'
                        , 'experience': 'C'
                        , 'sex': 'D'
                        , 'score': 'E'
                        , 'city': 'F'
                        , 'classify': 'G'
                        , 'wealth': 'H'
                        , 'sign': 'I'
                    }
                }, function (data) {
                    // 还可以再进行数据梳理
                    // 如果不需要展示直接上传，可以再次 $.ajax() 将JSON数据通过 JSON.stringify() 处理后传递到后端即可
                    layer.open({
                        title: '文件转换结果'
                        , area: ['800px', '400px']
                        , tipsMore: true
                        , content: laytpl($('#LAY-excel-export-ans').html()).render({data: data, files: files})
                        , success: function () {
                            element.render('tab')
                            layui.code({})
                        }
                    })
                })
            } catch (e) {
                layer.alert(e.message)
            }
        })
    }
</script>

<!--显示上传excel列表-->
<script type="text/html" id="LAY-excel-export-ans">
    {{# layui.each(d.data, function(file_index, item){ }}
    <blockquote class="layui-elem-quote">{{d.files[file_index].name}}</blockquote>
    <div class="layui-tab">
        <ul class="layui-tab-title">
            {{# layui.each(item, function (sheet_name, content) { }}
            <li>{{sheet_name}}</li>
            {{# }); }}
        </ul>
        <div class="layui-tab-content">
            {{# layui.each(item, function (sheet_name, content) { }}
            <div class="layui-tab-item">
                <table class="layui-table">
                    {{# layui.each(content, function (row_index, value) { }}
                    {{# var col_index = 0 }}
                    <tr>
                        {{# layui.each(value, function (col_key, val) { }}
                        <td id="table-export-{{file_index}}-{{sheet_name}}-{{row_index}}-{{col_index++}}">{{val}}</td>
                        {{# });}}
                    </tr>
                    {{# });}}
                </table>
                <pre class="layui-code">{{JSON.stringify(content, null, 2)}}</pre>
            </div>
            {{# }); }}
        </div>
    </div>
    {{# }) }}
</script>