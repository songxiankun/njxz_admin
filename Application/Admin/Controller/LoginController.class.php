<?php

/**
 * 后台登录-控制器
 */
namespace Admin\Controller;
use Admin\Service\AdminService;
class LoginController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminService();
        
    }
    
    /**
     * 登录入口
     * (non-PHPdoc)
     * @see \Admin\Controller\BaseController::index()
     */
    function index() {
        $this->display();
    }
    
    /**
     * 用户登录
     *
     */
    public function login() {
        if(IS_POST) {
            $message = $this->service->login();
            $this->ajaxReturn($message);
            return;
        }
        if($_GET['do'] == 'exit') {
            unset($_SESSION['adminId']);
            $this->redirect('/Admin/Login/index');
        }
    }
    
    /**
     * 验证码
     */
    public function verify() {
        $conf = array(
            //'useZh'=>true,//使用中文
            'fontSize'=>14,
            'length'=>4,
            'imageW'=>95,//验证码宽度
            'imageH'=>33,//验证码
            'useNoise'=>true,//是否添加杂点
            //'codeSet'=>'0123456789',
        );
        $Verify = new \Think\Verify($conf);
        $Verify->entry();
    }
    
    /**
     * 验证码校验（备用）
     */
    public function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        $res = $verify->check($code, $id);
        $this->ajaxReturn($res, 'json');
    }
    
}