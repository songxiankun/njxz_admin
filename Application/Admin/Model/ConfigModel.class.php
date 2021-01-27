<?php

/**
 * 配置-模型
 */
namespace Admin\Model;
use Common\Model\CBaseModel;
class ConfigModel extends CBaseModel {
    function __construct() {
        parent::__construct('config');
    }
}