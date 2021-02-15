<?php


namespace Home\Controller;


use Home\Service\UserService;

class UserController extends BaseController
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct($flag = false)
    {
        parent::__construct($flag);
        $this->userService = new UserService();
    }

    /**
     * 用户注册
     */
    public function register()
    {
        if (IS_POST) {
            $res = $this->userService->doRegister();
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 用户状态激活
     */
    public function status() {
        $res = $this->userService->updateStatus();
        $this->redirect(C('api'), [], 1500, $res['msg']);
    }
}