<?php


namespace Admin\Controller;

use Admin\Model\EnginRoomModel;
use Admin\Service\EnginRoomService;

/**
 * @brief 实验室管理控制器
 * Class EnginRoomController
 * @package Admin\Controller
 */
class EnginRoomController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new EnginRoomService();
        $this->mod = new EnginRoomModel();
    }

    /**
     * @desc 机房实验室 列表获取
     * @param array $data
     */
    public function index($data = [])
    {
        return parent::index($data);
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
            //var_dump($info);die();
        }else{
            foreach ($data as $key=>$val) {
                $info[$key] = $val;
            }
        }
        $this->assign('info',$info);
        $this->render();
    }
}