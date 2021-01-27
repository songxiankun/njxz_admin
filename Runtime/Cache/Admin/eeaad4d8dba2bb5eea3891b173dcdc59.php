<?php if (!defined('THINK_PATH')) exit();?>

	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/animate.css" media="all">
    <style type="text/css" media="screen">
    	body{ background: #ffffff;overflow-x: hidden; }
		.larry-grid{
			background: #ffffff;
			padding-top: 100px;
		}
		.larry-box{
			padding-left: 50px;
			padding-right: 50px;
			margin: 0 auto;
			text-align: center;
		}
		.left{
			padding-right: 80px;
		}
		.left h1{
			padding-top: 135px;
				font-size: 60px;
			font-weight: 700;
			color: #555555;
			margin-bottom: 20px;
		}
		.left h2{
			display: block;
			font-size: 1.5em;
			-webkit-margin-before: 0.83em;
			-webkit-margin-after: 0.83em;
			-webkit-margin-start: 0px;
			-webkit-margin-end: 0px;
			font-weight: bold;
			color: #777777;
		}
    </style>

<div class="larry-grid larry-wrapper">
    <div class="larry-box clearfix ">
        <div class="inline-block left animated fadeInLeft">
            <!-- <h1>  404</h1> -->
            <img src="/Public/Admin/images/404.png">
            <h2>亲，您当前访问的页面不存在，请您仔细检查~</h2>
        </div>
        <div class="inline-block right animated LarryRight">
            <a href="#" data-ke-src="#" data-ke-onclick="go();"><img src="/common/images/404.gif" alt=""></a>
        </div>
    </div>
</div>