<?php


namespace Home\Controller;


use Firebase\JWT\JWT;
use Home\Model\AdminModel;
use Think\Controller;
use Think\Verify;

/**
 * 基础控制器
 * Class BaseController
 * @package Home\Controller
 */
//解决API文档跨域调试请求的问题
header("Access-Control-Allow-Origin: *");
class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证码
     * author：songxk
     */
    public function verify()
    {
        $conf = array(
            //'useZh'=>true,//使用中文
            'fontSize' => 18,
            'length' => 4,
            'imageW' => 128,//验证码宽度
            'imageH' => 37,//验证码
            'useNoise' => true,//是否添加杂点
            //'codeSet'=>'0123456789',
        );
        $Verify = new Verify($conf);
        $Verify->entry();
    }
}