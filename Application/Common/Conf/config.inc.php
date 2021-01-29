<?php
/**
 * 系统常规配置
 */

//定义域名常量
define('MAIN_URL','http://admin.njxzc.edu.cn');
define('IMG_URL','http://images.njxzc.edu.cn');
define('ATTACHMENT_PATH', '/Applications/MxSrvs/www/njxz/admin/Uploads');
define('IMG_PATH', ATTACHMENT_PATH."/img");
define('FILE_PATH', ATTACHMENT_PATH."/file");
define('UPLOAD_TEMP_PATH', IMG_PATH . '/temp');

return array(
    'SITE_NAME' => '南京晓庄机房管理平台',
    'NICK_NAME' => '南京晓庄',
    'DB_CONFIG' => 'mysql://njxz:njxz2020.!@127.0.0.1:3306/njxz',
    // 'DB_CONFIG' => 'mysql://root:@127.0.0.1:3306/njxz',
//    'DB_CONFIG' => 'mysql://njxz_laboratory:rZ8dXbW8mYktLX5B@112.124.25.211:3306/njxz_laboratory',
    'CACHE_CONFIG'=>'redis://:@127.0.0.1:6379/2',
    'DB_PREFIX' => 'njxz_',
    'DB_CHARSET' => 'utf8mb4',
    'UPLOAD' => array(
        'UPLOAD_IMG_EXT' => 'jpg|png|gif|bmp|jpeg',
        'UPLOAD_IMG_SIZE' => 1024*10,//最大上传10MB文件
        'UPLOAD_IMG_NUM' => 9,//最大上传张数^M
        'UPLOAD_FILE_EXT' => 'xls|xlsx|pdf|PcbLib|rar|doc|png|jpg',
        'UPLOAD_FILE_SIZE' => 1024*10,//最大上传10MB文件
        'UPLOAD_FILE_NUM' => 1,//最大上传数
    ),
    'CKEY' => 'NJXZ',//缓存前缀^M
);