<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="Author" content="larry"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="Shortcut Icon" href="/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/console.css" media="all">
    <style type="text/css">
        .layuiadmin-badge {
            top: 50%;
            margin-top: -9px;
            color: #01AAED;
        }

        .layuiadmin-card-list {
            padding: 15px;
        }

        .layuiadmin-card-list p.layuiadmin-big-font {
            font-size: 36px;
            color: #666;
            line-height: 36px;
            padding: 5px 0 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-all;
            white-space: nowrap;
        }

        .layuiadmin-span-color {
            font-size: 14px;
            padding-left: 5px;
        }

        .layuiadmin-badge, .layuiadmin-btn-group, .layuiadmin-span-color {
            position: absolute;
            right: 15px;
        }
    </style>
</head>
<body class="larry-bg-gray">
<div class="layui-fluid">
    <div class="larry-container animated fadeIn">
        <!-- 统计模块面板 -->
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        维修申请
                        <span class="layui-badge layui-bg-orange layuiadmin-badge">今日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"><?php echo ($todayNums1); ?></p>
                        <p>维修申请总数
                            <span class="layuiadmin-span-color"><?php echo ($allNums1); ?> <i
                                    class="layui-inline layui-icon layui-icon-flag"></i></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        维修中
                        <span class="layui-badge layui-bg-black layuiadmin-badge">今日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"><?php echo ($todayNums2); ?></p>
                        <p>维修中总数
                            <span class="layuiadmin-span-color"><?php echo ($allNums2); ?> <i
                                    class="layui-inline layui-icon layui-icon-flag"></i></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        维修完成
                        <span class="layui-badge layui-bg-red layuiadmin-badge">今日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"><?php echo ($todayNums3); ?></p>
                        <p>维修完成总数
                            <span class="layuiadmin-span-color"><?php echo ($allNums3); ?> <i
                                    class="layui-inline layui-icon layui-icon-flag"></i></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        维修订单
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">今日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font"><?php echo ($todayNums4); ?></p>
                        <p>维修订单总数
                            <span class="layuiadmin-span-color"><?php echo ($allNums4); ?> <i
                                    class="layui-inline layui-icon layui-icon-flag"></i></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--    配置    -->
        <div class="layui-row layui-col-space15">
            <form class="layui-form">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            系统模式设置(全局生效)
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <div class="layui-form-item">
                                <label class="layui-form-label">委托模式</label>
                                <div class="layui-input-inline">
                                    <?php if($configInfo[0]['status'] == 1): ?><input type="checkbox" id="delegate" checked name="<?php echo ($configInfo[0]['id']); ?>" lay-filter="delegateMod"
                                               lay-skin="switch">
                                        <?php else: ?>
                                        <input type="checkbox" id="delegate" name="<?php echo ($configInfo[0]['id']); ?>" lay-filter="delegateMod"
                                               lay-skin="switch"><?php endif; ?>
                                </div>

                                <label class="layui-form-label">消息提醒模式</label>
                                <div class="layui-input-inline">
                                    <?php if($configInfo[1]['status'] == 1): ?><input type="checkbox"  id="info" checked name="<?php echo ($configInfo[1]['id']); ?>" lay-filter="infoMod" lay-skin="switch">
                                        <?php else: ?>
                                        <input type="checkbox" id="info" name="<?php echo ($configInfo[1]['id']); ?>" lay-filter="infoMod" lay-skin="switch"><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 加载js文件-->
<script type="text/javascript" src="/Public/Admin/layui/layui.js"></script>
<script type="text/javascript">
    layui.cache.page = 'Main';
    layui.config({
        version: "2.0.7",
        base: '/Public/Admin'
    }).extend({
        larry: '/js/base'
    }).use(['larry', 'form'], function () {
        var form = layui.form, //只有执行了这一步，部分表单元素才会自动修饰成功
            $ = layui.$;
        // 委托模式设置
        form.on('switch(delegateMod)', function (data) {
            var msg_ = this.checked ? '全局开启委托模式' : '全局关闭委托模式';
            layer.tips(msg_, data.othis);
            // AJAX 提交开启信息
            $.ajax({
                url: "/Config/updateConfig",
                method: "POST",
                data: { 'status' : this.checked ? '1' : '0', 'id': $("#delegate").attr('name') },
                dataType: "json",
                success : function (data) {
                    console.log(data)
                    if (data.success) {
                        return;
                    }
                    layer.tips(data.msg, data.othis);
                }
            });
        });

        // 发送消息方式
        // 委托模式设置
        form.on('switch(infoMod)', function (data) {
            var msg_ = this.checked ? '订单发送邮箱提醒' : '关闭邮箱提醒';
            layer.tips(msg_, data.othis);
            // AJAX 提交开启信息
            $.ajax({
                url: "/Config/updateConfig",
                method: "POST",
                data: { 'status' : this.checked ? '1' : '0', 'id': $("#info").attr('name') },
                dataType: "json",
                success : function (data) {
                    console.log(data)
                    if (data.success) {
                        return;
                    }
                    layer.tips(data.msg, data.othis);
                }
            });
        });
        //但是，如果你的HTML是动态生成的，自动渲染就会失效
        //因此你需要在相应的地方，执行下述方法来进行渲染
        form.render();
    });
</script>
</body>
</html>