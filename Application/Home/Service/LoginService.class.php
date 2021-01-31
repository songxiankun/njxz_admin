<?php


namespace Home\Service;

use Home\Model\AdminModel;
use Home\Model\UserModel;

/**
 * Login 服务层
 * Class LoginService
 * @package Home\Service
 */
class LoginService extends BaseService
{
    /**
     * 用户登陆操作
     * @return array
     */
    public function doLogin()
    {
        // 获取提交信息
        $post = I('post.', '', 'trim');

        $username = $post['username'];
        $password = $post['password'];
        $identify = $post['identify'];

        // 参数检测
        if (!$username) {
            return message("请输入用户名", false, "username");
        }

        if (!$password) {
            return message("请输入密码", false, "password");
        }

        if (!$identify) {
            return message("请选择身份", false, "password");
        }

        // 数据查询--用户是否存在
        $mod = null;
        $info = "";
        if (in_array($identify, [2, 3])) {  // 机房管理员
            $mod = new AdminModel();
            $info = $mod->where([
                'num' => $username,
                'mark' => 1,
            ])->find();
        } else if ($identify == 1) {
            // TODO 教师
        } else if ($identify == 4) {
            // 维修人员
            $mod = new UserModel();
            $info = $mod->where([
                'job_num' => $username,
                'mark' => 1,
            ])->find();
        }

        if (!$info) {
            return message("您的用户名不正确", false, "username");
        }

        $password = $this->password($password);

        // 判断密码是否正确
        if ($password != $info['password']) {
            return message("您的登录密码不正确", false, "password");
        }

        if ($info['status'] != 1) {
            return message("您的帐号已被禁言，请联系管理员", false);
        }

        if ($identify == 2) { // 机房管理员
            $role_arr = explode(',', $info['role_ids']);   // 4 5
            if (!in_array(5, $role_arr)) {
                return message("身份不匹配", false, []);
            }
        } else if ($identify == 3) {  // 审核人员
            $role_arr = explode(',', $info['role_ids']);   // 4 5
            if (!in_array(3, $role_arr)) {
                return message("身份不匹配", false, []);
            }
        }
        // token数据解析
        $arrToken = $this->dataToken($info['token']);

        // 是否为空
        if (empty($arrToken)) {
            return message("TOKEN过期，请重新登陆", false, []);
        }

        $token = $info['token'];
        // 如果当前token过期则生成新的token
        if ($arrToken['exp'] < time()) {
            $token = $this->getToken($info['id'], $identify, $info['realname'], $info['organization_id'], $info['dept_id']);
            $info['token'] = $token;
            $mod->where(['id' => $info['id']])->save($info);
        }

        return message("登录成功", true, [
            'token' => $token,
        ]);
    }

    /**
     * 获取组合密码
     * @param $password
     * @return string
     * @author：songxk
     */
    private function password($password)
    {
        $password = md5(md5($password));
        return $password;
    }
}