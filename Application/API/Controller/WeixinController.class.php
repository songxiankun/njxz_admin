<?php
/**
 * Created by PhpStorm.
 */

namespace API\Controller;


use API\Service\UserService;
use API\Service\WeixinService;

class WeixinController extends APIBaseController
{
    private $wx_service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new UserService();
        $this->wx_service = new WeixinService();
    }

    /**
     * @brief 根据微信code通过公众号授权获取用户信息
     */
    public function getUserInfo()
    {
        $code = $this->req['code'];
        $result = $this->service->getUserInfo($code);
        $this->ajaxReturn($result);
    }

    /**
     * @brief 获取js-sdk权限签名
     */
    public function getJsSdkSignature()
    {
        $url = $this->req['url'];
        $result = $this->wx_service->getJsSdkSignature($url);
        $this->ajaxReturn($result);
    }
}
