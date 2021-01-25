<?php

/**
 * 组织机构-控制器
 */
namespace Admin\Controller;
use Admin\Model\AdminOrgModel;
use Admin\Service\AdminOrgService;
class AdminOrgController extends BaseController {
    function __construct() {
        parent::__construct();
        $this->mod = new AdminOrgModel();
        $this->service = new AdminOrgService();
    }
    
    /**
     * 删除
     * (non-PHPdoc)
     * @see \Admin\Controller\BaseController::drop()
     */
    function drop() {
        if(IS_POST) {
            $id = I('post.id');
            $count = M("admin")->where(['organization_id'=>$id])->count();
            if($count) {
                $this->ajaxReturn(message("当前组织机构已经在使用中，无法删除",false));
                return;
            }
            parent::drop();
        }
    }
    
}