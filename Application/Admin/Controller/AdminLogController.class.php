<?php

/**
 * 登录日志-控制器
 */
namespace Admin\Controller;
use Admin\Model\AdminLogModel;
use Admin\Service\AdminLogService;
class AdminLogController extends BaseController {
    function __construct() {
        parent::__construct();
        $this->mod = new AdminLogModel();
        $this->service = new AdminLogService();
    }
}