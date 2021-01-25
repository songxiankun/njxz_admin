<?php

/**
 * 配置-控制器
 */
namespace Admin\Controller;
use Admin\Model\ConfigModel;
use Admin\Service\ConfigService;

class ConfigController extends BaseController {
    function __construct() {
        parent::__construct();
        $this->mod = new ConfigModel();
        $this->service = new ConfigService();
    }

    public function updateConfig()
    {
        $res = $this->service->update();
        $this->ajaxReturn($res);
    }
}