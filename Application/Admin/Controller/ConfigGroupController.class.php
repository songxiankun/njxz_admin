<?php

/**
 * 配置分组-控制器
 */
namespace Admin\Controller;
use Admin\Model\ConfigGroupModel;
use Admin\Service\ConfigGroupService;
class ConfigGroupController extends BaseController {
    function __construct() {
        parent::__construct();
        $this->mod = new ConfigGroupModel();
        $this->service = new ConfigGroupService();
    }
}