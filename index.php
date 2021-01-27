<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

//定义应用名称
define('APP_NAME','./Application');

// 定义运行时目录
define('RUNTIME_PATH','./Runtime/');

//定义默认模块
define('BIND_MODULE','Home');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单

//<!--<center>-->
//<!--    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">-->
//<!--        <legend>上传数据文件</legend>-->
//<!--    </fieldset>-->
//
//<!--    <div class="layui-upload-drag" id="uploadExcel">-->
//<!--        <i class="layui-icon"></i>-->
//<!--        <p>点击上传，或将文件拖拽到此处</p>-->
//<!--        <div class="layui-hide" id="uploadExcel_">-->
//<!--            <hr>-->
//<!--            <img src="" alt="上传成功后渲染" style="max-width: 196px">-->
//<!--        </div>-->
//<!--    </div>-->
//<!--    <br>-->
//<!--    <br>-->
//<!--    <br>-->
//<!--    <div class="layui-btn layui-btn-radius" id="import"> 点击上传</div>-->
//<!--</center>-->
//
//
//<!--<script>-->
//<!--    layui.use('upload', function () {-->
//<!--        var $ = layui.jquery-->
//<!--            , upload = layui.upload;-->
//<!--        //拖拽上传-->
//<!--        upload.render({-->
//<!--            elem: '#uploadExcel'-->
//<!--            , url: '/Devices/import'           //  上传接口-->
//    <!--            , accept: 'file'    //  普通文件-->
//    <!--            , exts: 'xls|xlsx'  //  只允许上传excel文件-->
//    <!--            , auto: false       //  不自动上传-->
//    <!--            // ,size: 60        //  限制文件大小，单位 KB-->
//<!--            , bindAction: '#import'  //  指向一个按钮触发上传-->
//    <!--            , before: function (obj) {     //  obj参数包含的信息，跟 choose回调完全一致，可参见上文。-->
//        <!--                layer.load();           //  上传loading-->
//        <!--            }-->
//<!--            , progress: function (n, elem) {-->
//<!--                var percent = n + '%' //获取进度百分比-->
//            <!--                elem.progress('demo', percent);-->
//<!--            }-->
//<!--            , done: function (res) {-->
//<!--                console.log(res);-->
//<!--                layer.closeAll('loading'); //关闭loading-->
//<!--                console.log(res)-->
//<!--            }-->
//<!--            , error: function (index, upload) {-->
//<!--                layer.closeAll('loading'); //关闭loading-->
//<!--            }-->
//<!--        });-->
//<!--    });-->
//<!--</script>-->