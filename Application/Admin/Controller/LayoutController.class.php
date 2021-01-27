<?php

/**
 * 布局-控制器
 */
namespace Admin\Controller;
use Admin\Model\LayoutModel;
use Admin\Service\LayoutService;
class LayoutController extends BaseController {
    function __construct() {
        parent::__construct();
        $this->mod = new LayoutModel();
        $this->service = new LayoutService();
    }
}