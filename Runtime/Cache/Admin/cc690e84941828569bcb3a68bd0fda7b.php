<?php if (!defined('THINK_PATH')) exit(); if(in_array('sys:' . lcfirst($app) . ':setAuth',$funcList)): ?><a class="layui-btn layui-btn-primary layui-btn-xs btnSetAuth" lay-event="auth"><i class="layui-icon">&#xe631;</i><?php echo ($funcName); ?></a><?php endif; ?>