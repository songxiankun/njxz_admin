<?php if (!defined('THINK_PATH')) exit(); if(in_array('sys:' . lcfirst($app) . ':detail',$funcList)): ?><a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail"><i class="layui-icon">&#xe63c;</i><?php echo ($funcName); ?></a><?php endif; ?>