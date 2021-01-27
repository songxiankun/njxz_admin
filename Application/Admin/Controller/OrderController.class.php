<?php


namespace Admin\Controller;

use Admin\Model\OrderModel;
use Admin\Service\OrderService;

/**
 * Class OrderController
 * @package Admin\Controller
 * 订单控制器
 */
class OrderController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new OrderModel;
        $this->service = new OrderService();
    }

    /**
     * Notes: 账单流水列表
     * User: songxk
     * DateTime: 2020/8/26 7:24 上午
     * @param array $data
     */
    public function index($data = [])
    {
        if (IS_POST) {
            $message = $this->service->getList();
            $this->ajaxReturn($message);
            return;
        }
        foreach ($data as $key => $val) {
            $this->assign($key, $val);
        }
        $this->render();
    }

    /**
     * Notes: 维修流水详情
     * User: songxk
     * DateTime: 2020/9/8 5:36 下午
     */
    public function detail()
    {
        $id = I("get.id", 0);
        if ($id) {
            $info = $this->mod->getInfo($id);
            $info = $this->service->detail($info);
            $this->assign('info', $info);
        }
        $this->render();
    }
}