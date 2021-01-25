<?php


namespace Admin\Controller;

use Admin\Model\RepairApplyModel;
use Admin\Service\RepairApplyService;

/**
 * Class RepairApplyController  维修申请订单控制器
 * @package Admin\Controller
 */
class RepairApplyController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new RepairApplyModel();
        $this->service = new RepairApplyService();
    }

    /**
     * @desc 维修申请列表信息
     * @param array $data
     */
    public function index($data = [])
    {
        if(IS_POST) {
            $message = $this->service->getList();
            $this->ajaxReturn($message);
            return;
        }
        foreach ($data as $key=>$val) {
            $this->assign($key,$val);
        }
        $this->render();
    }

}