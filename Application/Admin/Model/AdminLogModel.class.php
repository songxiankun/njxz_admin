<?php

/**
 * 登录日志-模型
 */
namespace Admin\Model;
use Common\Model\CBaseModel;
class AdminLogModel extends CBaseModel {
    function __construct() {
        parent::__construct('admin_log');
    }
    
    /**
     * 获取缓存信息
     * (non-PHPdoc)
     * @see \Common\Model\CBaseModel::getInfo()
     */
    function getInfo($id) {
        $info = parent::getInfo($id,true);
        if($info) {
            //TODO...
        }
        return $info;
    }

    public function save($data = '', $options = array())
    {
        $this->add($data);
    }

}