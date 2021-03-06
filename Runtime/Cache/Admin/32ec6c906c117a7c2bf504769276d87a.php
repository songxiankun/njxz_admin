<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo ($siteName); ?></title>
	<meta name="keywords" content="<?php echo ($siteName); ?>" />
    <meta name="description" content="<?php echo ($siteName); ?>" />
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
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/animate.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/login.css" media="all">
</head>
<body>
<div class="layui-fluid">
	<div class="layui-row larryms-layout">
		<div class="layui-col-lg6  layui-col-md6 layui-col-sm10 layui-col-xs11 larry-main animated shake larry-delay2">
             <div class="title"><?php echo ($siteName); ?></div>
             <p class="info"></p>
             <div class="user-info">
             	  <div class="avatar"><img src="/Public/Admin/images/photo/admin.png" alt=""></div>
             	  <form class="layui-form" id="larry_form">
                        <div class="layui-form-item">
							<label class="layui-form-label">用户名:</label>
			 	            <input type="text" name="username" id="username" lay-verify="required" autocomplete="off" class="layui-input larry-input" placeholder="请输入您的用户名">
			            </div>
			            <div class="layui-form-item" id="password">
			 	            <label class="layui-form-label">密码:</label>
			 	            <input type="password" name="password" id="password" required lay-verify="required|password" autocomplete="off" class="layui-input larry-input" placeholder="请输入您的登录密码">
			            </div>
			            <div class="layui-form-item larry-verfiy-code" id="larry_code">
			 	            <input type="text" name="captcha" id="captcha" lay-verfy="required" autocomplete="off" class="layui-input larry-input" placeholder="输入验证码">
			 	            <div class="code">
					            <div class="arrow"></div>
								<div class="code-img">
									<img onClick="flushYzm();" src="<?php echo U('Login/verify');?>" title="看不清,点击更换验证码" alt="看不清,点击更换验证码" class="layui-disabled2" id="verify_img">
								</div>
					         </div>
			            </div>
			            <div class="layui-form-item">
			 	            <button class="layui-btn larry-btn" lay-filter="submit" lay-submit>立即登录</button>
			            </div>
             	  </form>
             </div>
             <!-- <div class="copy-right">© 2016-2017 Larry 版权所有  <a href="https://www.larrycms.com/" target="_blank">larrycms.com</a></div> -->
		</div>
	</div>
</div>
<!-- 加载js文件-->
<script type="text/javascript" src="/Public/Admin/layui/layui.js"></script> 
<script>
    if(window != top){
        sessionStorage.clear();
        top.location.href = location.href;
    }
    /^http(s*):\/\//.test(location.href) || alert('请先部署到 localhost 下再访问！！！');
    layui.cache.page = 'Login';
    layui.config({
       version:"2.0.7",
       base:'/Public/Admin'  //实际使用时，建议改成绝对路径
    }).extend({
        larry:'/js/base'
    }).use('larry');
</script>
</body>
</html>