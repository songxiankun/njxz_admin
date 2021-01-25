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
}