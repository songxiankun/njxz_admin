<?php

/**
 * 广告-模型
 */
namespace API\Model;
use Common\Model\CBaseModel;
class AdModel extends CBaseModel {
    function __construct() {
        parent::__construct('ad');
    }
    
    /**
     * 获取缓存信息
     * (non-PHPdoc)
     * @see \Common\Model\CBaseModel::getInfo()
     */
    function getInfo($id) {
        $info = parent::getInfo($id);
        if($info) {
            //广告封面
            if($info['cover']) {
                $info['cover_url'] = IMG_URL . $info['cover'];
            }
            
        }
        return $info;
    }
    
}