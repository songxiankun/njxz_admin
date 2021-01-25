<?php
/**
 * Created by PhpStorm.
 */

namespace Admin\Controller;


use Admin\Service\SmsService;

class SmsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new SmsService();
    }

    public function index($data = [])
    {
        if (IS_POST) {
            $message = $this->service->getList($id = '');
            $this->ajaxReturn($message);
            return;
        }
        foreach ($data as $key => $val) {
            $this->assign($key, $val);
        }
        $this->render();
    }

    public function edit()
    {
        if (IS_POST) {
            $result = $this->service->edit();
            $this->ajaxReturn($result);
        }
        $this->render();
    }
}
