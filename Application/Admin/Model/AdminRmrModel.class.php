<?php

/**
 * 角色人员关系-模型
 */
namespace Admin\Model;
use Common\Model\CBaseModel;
class AdminRmrModel extends CBaseModel {
    function __construct() {
        parent::__construct('admin_rmr');
    }
    
    /**
     * 获取缓存信息
     * (non-PHPdoc)
     * @see \Common\Model\CBaseModel::getInfo()
     */
    function getInfo($id) {
        $info = parent::getInfo($id);
        if($info) {
            //TODO...
        }
        return $info;
    }
    
}