<?php if (!defined('THINK_PATH')) exit();?>﻿<script type="text/javascript">
layui.cache.page = '<?php echo ($app); ?>'; 
//设定扩展的Layui模块的所在目录，一般用于外部模块扩展
layui.config({
	version:Math.random(),
	base:'/Public/Admin'
}).extend({
    larry:'/js/base',
    func:'module/func',
}).use(['larry']);
</script>

</body>
</html>