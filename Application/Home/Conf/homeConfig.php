<?php

//【邮件配置】
return array(
    'send_email_config' => array(
        'charset'       => 'UTF-8',
        'host'          => 'smtp.163.com',
        'smtpAuth'      => true,
        'smtpAutoTLS'   => false,
        'username'      => 'sxk_cmz@163.com',
        'password'      => 'OPEUFXGATRKPQUWQ',
        'port'          => 25,
        'nickname'      => '南京晓庄学院·信息工程学院·实验室'
    ),
    'user_token_key'   => "MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAKG21GW0UttFcyu85gSMG1MJ/9zJ9VYPqm8wFlMrDR8vvEjhflvlVrzi6dhfVUbAql5IHKEEKTSNMdyJ72ZHTVcCAwEAAQ==",             // 这里是自定义的一个随机字串，应该写在config文件中的，解密时也会用，相当    于加密中常用的 盐 sal
    'sys_info_config'  => array(
        'type_delegate'       => 1,          //  委托模式
        'type_mail'           => 2           //  发送邮件
    ),
    'code'  => array(
        'ok'            => 200,
        'error_token'   => 404,
        'email_close'   => 405,
    ),
    'user_type' => array(    // USER table user identify  source 字段
        'teacher'   => 2,
        'woker'     => 1
    ),
    'order_prefix' => 'NJXZ',
    'email_url'    => 'http://home.njxzc.edu.cn',
    'api'          => 'http://api.njxzc.edu.cn'
);