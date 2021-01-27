<?php

/**
 * 部门-服务类
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
use Admin\Model\AdminDepModel;
class AdminDepService extends ServiceModel {
    function __construct() {
        parent::__construct();
        $this->mod = new AdminDepModel();
    }
    
    /**
     * 获取数据列表
     * (non-PHPdoc)
     * @see \Admin\Model\BaseModel::getList()
     */
    function getList() {
        $list = $this->mod->getChilds(0,1);
        return message('操作成功',true,$list);
    }
    
}