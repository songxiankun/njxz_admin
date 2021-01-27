<?php
/**
 * Created by PhpStorm.
 */

namespace API\Controller;


use API\Service\WeiboService;

class WeiboController extends APIBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new WeiboService();
    }

    /**
     * @brief 根据微博code通过微博授权获取用户信息
     */
    public function getUserInfo()
    {
        $code = $this->req['code'];
        $result = $this->service->getUserInfoByWeiBo($code);
        $this->ajaxReturn($result);
    }
}
