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
        parent::__construct(false);
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
}