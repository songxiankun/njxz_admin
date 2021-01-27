<?php

/**
 * 广告描述-服务类
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
use Admin\Model\AdSortModel;
class AdSortService extends ServiceModel {
    function __construct() {
        parent::__construct();
        $this->mod = new AdSortModel();
    }
    
    /**
     * 获取数据列表
     * @see \Admin\Model\BaseModel::getList()
     */
    function getList() {
        $param = I("request.");
        
        $map = [];
        
        //查询条件
        $name = trim($param['name']);
        if($name) {
            $map['name'] = array('like',"%{$name}%");
        }
        
        return parent::getList($map);
        
    }
    
}