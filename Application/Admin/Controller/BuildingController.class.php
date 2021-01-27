<?php


namespace Admin\Controller;

use Admin\Model\BuildingModel;
use Admin\Service\BuildingService;

/**
 * 楼名控制器
 * Class BuildingController
 * @package Admin\Controller
 */
class BuildingController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new BuildingService();
        $this->mod = new BuildingModel();
    }

    /**
     * @desc 楼层列表
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

    /**
     * @desc 新增 ｜ 更新
     * @param array $data
     */
    public function edit($data = array())
    {
        if(IS_POST) {
            $message = $this->service->edit();
            $this->ajaxReturn($message);
            return ;
        }

        $id = I("get.id",0);
        if($id) {
            $info = $this->mod->getInfo($id);
        }else{
            foreach ($data as $key=>$val) {
                $info[$key] = $val;
            }
        }
        $this->assign('info',$info);
        $this->render();
    }
}