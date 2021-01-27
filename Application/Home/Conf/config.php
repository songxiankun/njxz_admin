<?php

//是否调试模式
if(APP_DEBUG){
    $error = THINK_PATH.'Tpl/think_exception.tpl';
}else{
    $error = "./Application/Home/View/Public/404.html";
}
return array(
    //'配置项'=>'配置值'

    'TMPL_EXCEPTION_FILE' => $error, // 默认错误跳转对应的模板文件

    //【加载扩展配置文件】
    'LOAD_EXT_CONFIG' => 'homeConfig',//扩展配置可以支持自动加载额外的自定义配置文件

    //【设置模板替换标记】
    'TMPL_PARSE_STRING' =>  array(
        '__HOME__' => __ROOT__.'/Public/Home',//后台资源文件目录
        '__APP__'    => "/Application/",
    ),

);