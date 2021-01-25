<?php

namespace Home\Controller;

use Home\Service\AdminService;
use Home\Service\UserService;

/**
 * 前端首页
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends BaseController
{
    /**
     * @var AdminService
     */
    private $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService();
    }

    /**
     * API 接口地址
     * @author songxk
     */
    public function index()
    {
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>【API接口】！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>', 'utf-8');
    }

    /**
     * 用户主页面渲染
     * @author songxk
     */
    public function main()
    {
        if (IS_POST) {
            $result = $this->adminService->initWeb();
            $this->ajaxReturn($result);
            return;
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 获取用户信息数据
     * @author songxk
     */
    public function getUserInfo()
    {
        if (IS_POST) {
            $identify = I("post.identify");
            $res = message('非法操作', false, []);
            if ($identify == 1) // 教师
            {}
            else if (in_array($identify, [2, 3]))   // 机房管理员
            {
                $res = $this->adminService->getInfo();
            }
            else if ($identify == 4) // 维修人员
            {
                $userService = new UserService();
                $res = $userService->getInfo();
            }
            $this->ajaxReturn($res);
            return;
        }
       $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 更新用户信息
     * @author songxk
     */
    public function editUserInfo()
    {
        if (IS_POST) {
            $identify = I("post.identify");
            $res = message('非法操作', false, []);
            if ($identify == 1) // 教师
            {}
            else if (in_array($identify, [2, 3]))   // 机房管理员
            {
                $res = $this->adminService->edit();
            }
            else if ($identify == 4) // 维修人员
            {
                $userService = new UserService();
                $res = $userService->edit();
            }
            $this->ajaxReturn($res);
            return;
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }


    /**
     * 更新用户信息
     * @author songxk
     */
    public function updatePassword()
    {
        if (IS_POST) {
            $identify = I("post.identify");
            $res = message('非法操作', false, []);
            if ($identify == 1) // 教师
            {}
            else if (in_array($identify, [2, 3]))   // 机房管理员
            {
                $res = $this->adminService->update();
            }
            else if ($identify == 4) // 维修人员
            {
                $userService = new UserService();
                $res = $userService->update();
            }
            $this->ajaxReturn($res);
            return;
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }
}