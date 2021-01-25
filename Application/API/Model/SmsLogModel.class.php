<?php

/**
 * 短信日志-模型
 */
namespace API\Model;
use Common\Model\CBaseModel;
class SmsLogModel extends CBaseModel {
    function __construct() {
        parent::__construct('sms_log');
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