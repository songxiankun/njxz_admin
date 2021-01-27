<?php 

/**
 * 后台常用基础参数配置
 */
return array(

    //是否菜单
    'IS_MENU' => array(
        1 => '是',
        2 => '否',
    ),
    
    //菜单类型
    'MENU_TYPE'=>array(
        1 => '模块',
        2 => '导航',
        3 => '菜单',
        4 => '节点',
    ),
    
    //站点类型
    'ITEM_TYPE'=>array(
        1 => '普通站点',
        2 => '其他',
    ),
    
    //所属平台
    'PLATFORM_TYPE'=>array(
        1 => 'APP端',
        2 => 'PC端',
        3 => '后台添加',
        4 => '小程序端',
    ),
    //订单状态
    'ORDER_STATUS'=>array(
        1 => "待支付",
        2 => "待发货(已付款)",
        3 => "已发货",
        4 => "已完成(已签收）",
        5 => "已取消",
        6 => "超时未支付",
        7 => "已关闭",
        8 => "待审核",
        9 => "审核失败"
    ),

    //广告类型
    'AD_TYPE'=>array(
        1 => '图片',
        2 => '文字',
        3 => '视频',
        4 => '推荐',
    ),

    //友链类型
    'LINK_CATE'=>array(
        1 => '友情链接',
        2 => '合作伙伴',
    ),
    
    //友链类型
    'LINK_TYPE'=>array(
        1 => '文字',
        2 => '图片',
    ),
    
    //系统推荐类型(布局、广告)
    'SYSTEM_RECOMM_TYPE'=>array(
        1 => '新闻资讯',
        2 => '其他',
    ),
    
    //短信状态
    'SMS_LOG_STATUS'=>array(
        1 => '成功',
        2 => '失败',
        3 => '待处理',
    ),
    
    //管理用户类型
    'ADMIN_USER_TYPE'=>array(
        1 => '系统用户',
        2 => '服务商',
    ),
    
    //配置类型
    'SYSTEM_CONFIG_TYPE'=>array(
        1 => '委托模式',
        2 => '邮件提醒'
    ),

    // devices 设备excel导入
    'DEVICES_COLUMNS' => array(
        'origin_num'  => '原资产编号',
        'num' => '资产编号',
        'device_name' => '资产名称',
        'type_name' => '型号',
        'norm' => '规格',
        'count' => '数量/面积',
        'money' => '原值',
        'achieve_time' => '取得日期',
        'department_name' => '使用部门',
        'admin_name' => '使用人/保管人',
        'address' => '存放地'
    ),
);

?>