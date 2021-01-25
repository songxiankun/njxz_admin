<?php

/**
 * 组织机构-服务类
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
use Admin\Model\AdminOrgModel;
class AdminOrgService extends ServiceModel {
    function __construct() {
        parent::__construct();
        $this->mod = new AdminOrgModel();
    }
    
    /**
     * 获取数据列表
     * (non-PHPdoc)
     * @see \Admin\Model\BaseModel::getList()
     */
    function getList() {
        $param = I("request.");
        
        $map = [];
        //查询条件
        $keywords = trim($param['keywords']);
        if($keywords) {
            $map['name'] = array('like',"%{$keywords}%");
        }
        
        return parent::getList($map);
    }
    
    /**
     * 添加或编辑
     * (non-PHPdoc)
     * @see \Admin\Model\BaseModel::edit()
     */
    function edit() {
        $data = I('post.', '', 'trim');
        $logo = trim($data['logo']);
        
        //LOGO
        if(strpos($logo, "temp")) {
            $data['logo'] = \Zeus::saveImage($logo, 'adminOrg');
        }
        return parent::edit($data);
    }
    
}