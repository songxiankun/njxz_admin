<?php


namespace Admin\Controller;


use Admin\Model\DevicesTypeModel;
use Admin\Service\DevicesTypeService;

class DevicesTypeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new DevicesTypeService();
        $this->mod = new DevicesTypeModel();
    }

    /**
     * @desc 设备类型列表
     * @param array $data
     */
    public function index($data = [])
    {
        return parent::index($data);
    }
}