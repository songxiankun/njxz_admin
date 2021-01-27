<?php

/**
 * [角色、人员]菜单-模型
 */
namespace Admin\Model;
use Common\Model\CBaseModel;
class AdminRomModel extends CBaseModel {
    function __construct() {
        parent::__construct('admin_rom');
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