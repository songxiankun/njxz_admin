<?php


namespace Home\Controller;

use Home\Service\LoginService;

/**
 * 登陆控制器
 * Class LoginController
 * @package Home\Controller
 */
class LoginController extends BaseController
{

    /**
     * @var LoginService
     */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new LoginService();
    }

    /**
     * 用户登陆器
     * @author： songxk
     */
    public function login()
    {
        if (IS_POST) {
            $message = $this->service->doLogin();
            $this->ajaxReturn($message);
            return;
        }
        $this->ajaxReturn(message('非法请求', false,[]));
    }

    /**
     * 检测是否登陆，如果登陆就跳回主页面
     * @author songxk
     */
    private function checkLogin()
    {
        if (isset($_SESSION['adminId']) && isset($_SESSION['roles'])) {
            $this->redirect('/Index/index');
            exit;
        }
    }
}