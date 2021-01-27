<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo ($siteName); ?></title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
    <meta name="Author" content="larry" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<link rel="Shortcut Icon" href="/Public/Admin/images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/global.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/larryms.css" media="all">
	<!-- load css -->
    <script>
        /^http(s*):\/\//.test(location.href) || alert('【系统】提示您 请先部署到 localhost 下再访问！！！');
    </script>
</head>
<body>
<div class="layui-layout layui-layout-admin layui-fluid larryms-layout" id="larry_layout">
	<!-- 顶部导航 -->
	<div class="layui-header larryms-header" id="larry_head">
        <div class="larryms-topbar-left" id="topbarL">
        	<span class="mini-logo"><img src="/Public/Admin/images/logo_mini.png" alt=""></span>
         	<a class="layui-logo larryms-logo"><?php echo ($nickName); ?></a>
         	<span class="larryms-switch larryms-icon-fold" id="menufold"><i class="larry-icon larry-fold7"></i></span>
         	<div class="larryms-mobile-menu" id="larrymsMobileMenu"><i class="larry-icon yun-liebiao"></i></div>
        </div>
        <div class="larryms-extend">
         	<div class="larryms-topbar-menu larryms-hide-xs clearfix">
         	    <ul class="larryms-nav clearfix fl" id="larryms_top_menu" lay-filter='TopMenu'>
         	    	 <!-- 若开启顶部菜单，此处动态生成 -->
                     
         	    </ul>
         	    <div class="dropdown extend-show" id="larryms_topSubMenu">
         	    	 <i class="submenubtn larry-icon larry-sandianshu" id="subMenuButton"></i>
         	    	 <ul class="dropdown-menu larryms-nav" id="dropdown">
         	    	 	
         	    	 </ul>
         	    </div>
            </div>
            <!-- 右侧常用菜单 -->
            <div class="larryms-topbar-right" id="topbarR">
            	<ul class="layui-nav clearfix">
                    <!-- <li class="layui-nav-item" lay-unselect>
                        <a id="message" class="message" data-group='0' data-url='html/message.html' data-id='1004' >
                            <i class="larry-icon larry-xiaoxi2" data-icon="larry-xiaoxi2" data-font="larry-icon"></i>
                            <cite>消息</cite>
                            <span class="layui-badge-dot"></span>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a id="lock"><i class="larry-icon larry-diannao1"></i><cite>锁屏</cite></a>
                    </li> -->
                    <li class="layui-nav-item">
                        <a id="fullScreen"><i class="larry-icon yun-quanping1"></i><cite>全屏切换</cite></a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a id="clearCached"><i class="larry-icon yun-qingchu"></i><cite>清除缓存</cite></a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a id="larryTheme"><i class="larry-icon yun-huabanzhuti"></i><cite>主题设置</cite></a>
                    </li>   
            		<li class="layui-nav-item exit" lay-unselect>
                        <a id="logout" data-url='/Login/login?do=exit'><i class="larry-icon yun-tuichu"></i><cite>退出</cite></a>
                    </li>
            	</ul>
            </div>
        </div>
	</div>
	<!-- 内容主体 -->
	<div class="larryms-body" id="larryms_body">
		<!-- 左侧导航区域 -->
		<div class="layui-side pos-a larryms-left layui-bg-black" id="larry_left">
			<div class="layui-side-scroll">
                <!-- 管理员信息      -->
                <div class="user-info">
                    <div class="photo">
                        <img src="<?php if($adminInfo["avatar_url"] != null): echo ($adminInfo["avatar_url"]); else: ?>/Public/Admin/images/user.jpg<?php endif; ?>" id="user_photo" alt="">
                    </div>
                    <p><span id="uname">【<?php echo ($adminInfo["realname"]); ?>】</span>您好！欢迎登录</p>
                </div>
                <!-- 系统菜单 -->
                <div class="sys-menu-box" >
                    <ul class="larryms-nav larryms-nav-tree" id="larryms_left_menu" lay-filter="LarrySide" data-group='0'>
                        <!-- 此次动态生成 -->
                        
                    </ul>
                </div>    
			</div> 
		</div>
		<!-- 右侧框架内容区域 -->
		<div class="layui-body pos-a larryms-right" id="larry_right">
			<div class="layui-tab larryms-tab" id="larry_tab" lay-filter="larryTab">
                <div class="larryms-title-box clearfix" id="larryms_title">
                    <div class="larryms-btn-default larryms-press larryms-pull-left hide" id="goLeft"><i class="larry-icon larry-top-left-jt"></i></div> 
                    <ul class="layui-tab-title larryms-tab-title" lay-allowclose='false' id="larry_tab_title" lay-filter='larrymsTabTitle'>
                        <li class="layui-this" id="larryms_home" lay-id="0" data-group="0" data-id="larryms-home" fresh="1" data-url="html/main1.html">
                            <i class="larry-icon yun-shouye" data-icon="yun-shouye" data-font="larry-icon"></i><cite>后台首页</cite>
                        </li>
                    </ul>
                    <div class="larryms-btn-group clearfix">
                        <div class="larryms-btn-default larryms-press larryms-pull-right hide" id="goRight"><i class="larry-icon larry-gongyongshuangjiantouyou"></i></div>
                        <div class="refresh larryms-press" id="larryms_refresh">
                            <i class="larry-icon yun-shuaxin"></i>
                            <cite>刷新</cite>
                        </div>
                        <div class="larryms-press often" lay-filter="larryOperate" id="buttonRCtrl">
                            <ul class="larryms-nav">
                                <li class="larryms-nav-item">
                                    <a class="top"><i class="larry-icon yun-dianji1"></i><cite>常用操作</cite><span class="larryms-nav-more"></span></a>
                                    <dl class="larryms-nav-child layui-anim layui-anim-upbit">
                                        <dd id="tabCtrD">
                                            <a data-ename="positionCurrent"><i class="larry-icon yun-shuangjiantou1"></i><cite>定位当前选项卡</cite></a>
                                        </dd>
                                        <dd id="tabCtrA">
                                            <a data-ename="closeCurrent"><i class="larry-icon yun-guanbi4"></i><cite>关闭当前选项卡</cite></a>
                                        </dd>
                                        <dd id="tabCtrB">
                                            <a data-ename="closeOther"><i class="larry-icon yun-guanbi1"></i><cite>关闭其他选项卡</cite></a>
                                        </dd>
                                        <dd id="tabCtrC">
                                            <a data-ename="closeAll"><i class="larry-icon yun-close-all"></i><cite>关闭全部选项卡</cite></a>
                                        </dd>
                                        <dd>
                                            <a data-ename="refreshAdmin"><i class="larry-icon yun-shuaxin1"></i><cite>刷新最外层框架</cite></a>
                                        </dd>
                                    </dl>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> 
                <!-- tab title end -->
                <div class="layui-tab-content larryms-tab-content" id="larry_tab_content">
                    <div class="layui-tab-item layui-show">
                        <iframe class="larry-iframe" data-id='0' name="ifr_0" id='ifr0'  src="<?php echo U('Index/main');?>" frameborder="no" border="0"></iframe>
                    </div>
                </div>
                <!-- tab content end -->
            </div>
		</div>
		<!-- 移动端支持 -->
		<div class="larryms-mobile-shade" id="larrymsMobileShade"></div>
	</div>
	<!-- 底部固定区域 -->
	<div class="layui-footer larryms-footer" data-show='on' id="larry_footer">
		 <div class="copyright inline-block pos-al"><a href="javascript:">最终解释权归【南京晓庄学院·信息工程学院】所有</a></div>
         <!--<p class="block">系统研发负责人：相约在冬季(QQ:1175401194)</p>-->
		 <div class="larryms-info inline-block pos-ar">当前版本：V3.2.0<i class="layui-icon">&#xe67c;</i></a></div>
	</div>
</div>
<!-- 加载js文件-->
<script type="text/javascript" src="/Public/Admin/layui/layui.js"></script>
<script type="text/javascript">
//layui.cache.menusUrl = '/assets/menudatas.json';//这里设置 菜单数据项接口地址 或data参数
layui.cache.menusUrl = "<?php echo U('Menu/getMenuList');?>";
layui.cache.page = '<?php echo ($app); ?>'; 
//说明：并非一个页面只能加载一个模块 可以这样定义：'index,common';也并非每个页面都要定义一个模块，事实上模块根据功能需要可以公用
//layui.cache.rightMenu = 'custom'; //默认开启页面右键菜单，设置为 custom 时需要自定义右键菜单，设置为false 关闭右键菜单
layui.config({
   version:"2.0.7",
   base:'/Public/Admin'  //实际使用时，建议改成绝对路径
}).extend({
    larry:'/js/base'
}).use('larry');
</script>
</body>
</html>