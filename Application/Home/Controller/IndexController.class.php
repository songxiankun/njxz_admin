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
        parent::__construct(true);
        $this->adminService = new AdminService();
    }

    /**
     * API 接口地址
     * @author songxk
     */
    public function index()
    {
        $this->ajaxReturn(message("欢迎使用南京晓庄学院api接口", true, [
                'TEAM'     =>  array(
                    'team_user'  => array(
                        'QQ' => '1281541477',
                        'WX' => '_kunkun99',
                        'mobile'    => '13584495195',
                        'email'     =>  's13584495195@163.com',
                        'school'    =>  '南京晓庄学院',
                        'belong'    =>  '信息工程学院',
                        'number'    =>  '17132521',
                        'username'  =>  '宋贤坤'
                    )
                 ),
                'currDate' => date("Y-m-d H:i:s", time()),
            ]));
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
     * 根据用户提供的token 获取用户的 id realname role
     */
    public function getInfoByToken()
    {
        $token = I("token");
        $this->ajaxReturn($this->dataToken($token));
    }

    /**
     * 获取用户信息数据
     * @author songxk
     */
    public function getUserInfo()
    {
        if (IS_POST) {
            $token = $this->dataToken(I("post.token"));
            $identify = $token['data']['data']['role'];
            // 身份
            $res = '';
            if ($identify == 1) // 教师
            {}
            else if (in_array($identify, [2, 3]))   // 机房管理员
            {
                $res = $this->adminService->getInfo($token['data']['data']['id']);
            }
            else if ($identify == 4) // 维修人员
            {
                $userService = new UserService();
                $res = $userService->getInfo($token['data']['data']['id']);
            }
            $this->ajaxReturn($res);
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
            $token = $this->dataToken(I("post.token"));
            $identify = $token['data']['data']['role'];
            $res = '';
            if ($identify == 1) // 教师
            {}
            else if (in_array($identify, [2, 3]))   // 机房管理员
            {
                $res = $this->adminService->edit($token['data']['data']['id']);
            }
            else if ($identify == 4) // 维修人员
            {
                $userService = new UserService();
                $res = $userService->edit($token['data']['data']['id']);
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
            $token = $this->dataToken(I("post.token"));
            $identify = $token['data']['data']['role'];
            $res = message('非法操作', false, []);
            if ($identify == 1) // 教师
            {}
            else if (in_array($identify, [2, 3]))   // 机房管理员
            {
                $res = $this->adminService->update($token['data']['data']['id']);
            }
            else if ($identify == 4) // 维修人员
            {
                $userService = new UserService();
                $res = $userService->update($token['data']['data']['id']);
            }
            $this->ajaxReturn($res);
            return;
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }
}