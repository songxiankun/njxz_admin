<?php


namespace Home\Controller;


use Home\Service\RepairApplicationService;

class RepairApplicationController extends BaseController
{
    /**
     * @var RepairApplicationService
     */
    private $service;

    public function __construct()
    {
        $this->service = new RepairApplicationService();
    }

    /**
     * 获取待审核表单列表
     * @author songxk
     */
    public function getApplyLists()
    {
        $data = $this->service->getList();
        $this->ajaxReturn($data);
    }

    /**
     * 根据id删除当前信息
     * @author songxk
     */
    public function deleteByID()
    {
        $res = $this->service->deleteById();
        $this->ajaxReturn($res);
    }

    /**
     * 获取维修申请订单信息
     * @author kunkun
     */
    public function getApplyInfo()
    {
        if (IS_POST) {  // post 请求
            $res = $this->service->getApplyInfo();
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn(message("非法请求", false, []));
    }

    /**
     * 获取订单信息
     * @author kunkun
     */
    public function getOrderInfo()
    {
        if (IS_POST) {  // post 请求
            $res = $this->service->getOrderInfo();
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn(message("非法请求", false, []));
    }

    /**
     * 更新申请订单状态
     * @author songxk
     */
    public function updateRepairInfo()
    {
        if (IS_POST) {
            $res = $this->service->update();
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn(message("非法请求", false, []));
    }

    /**
     * 维修人员接单
     * @author songxk
     */
    public function updateOrder() {
        if (IS_POST) {
            $res = $this->service->updateOrder();
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn(message("非法请求", false, []));
    }
}