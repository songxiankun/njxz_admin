<?php


namespace Admin\Controller;

use Admin\Model\UserModel;
use Admin\Service\UserService;

/**
 * Class UserController
 * @package Admin\Controller
 * 维修用户管理控制器
 */
class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new UserModel();
        $this->service = new UserService();
    }

    /**
     * @desc 维修人员列表
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
     * 编辑或更新
     * @param array $data
     */
    public function edit($data = array())
    {
        if(IS_POST) {
            $message = $this->service->edit($data);
            $this->ajaxReturn($message);
            return ;
        }
        $id = I("get.id",0);
        if($id) {
            $info = $this->mod->getInfo($id, false);
        }else{
            foreach ($data as $key=>$val) {
                $info[$key] = $val;
            }
        }
        $this->assign('info', $info);
        $this->render();
    }

    /**
     * Notes: 用户详情
     * User: songxk
     * DateTime: 2020/8/26 6:43 上午
     */
    public function detail()
    {
        if(IS_POST) {
            $message = $this->service->edit();
            $this->ajaxReturn($message);
            return ;
        }
        $id = I("get.id",0);
        if($id) {
            $info = $this->mod->getInfo($id);
            $info = $this->service->operate($info);
            $this->assign('info',$info);
        }
        $this->render();
    }
}