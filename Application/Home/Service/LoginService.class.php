<?php


namespace Home\Service;

use Firebase\JWT\JWT;
use Home\Model\AdminModel;
use Home\Model\UserModel;
use Think\Model;
use Think\Verify;

/**
 * Login 服务层
 * Class LoginService
 * @package Home\Service
 */
class LoginService extends Model
{
    public function __construct()
    {
    }

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
//        $captcha = $post['captcha'];
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

//        if (!$captcha) {
//            return message('验证码不能为空', false, "captcha");
//        } else if (!$this->check_verify($captcha) && $captcha != 520) {
//            return message('验证码不正确', false, "captcha");
//        }


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
            // TODO 维修人员
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
            if (!in_array(4, $role_arr)) {
                return message("身份不匹配", false, []);
            }
        }

//        if (in_array($identify, [2, 3])) {
//            $token = $this->getToken($info['id'], $identify);
//            $info['token'] = json_decode($token, true)['token'];
//            $mod->where(['id' => $info['id']])->save($info);
//        } else {
            $token = $this->getToken($info['id'], $identify);
            $info['token'] = json_decode($token, true)['token'];
            $mod->where(['id' => $info['id']])->save($info);
//        }

        return message("登录成功", true, [
            'token' => json_decode($token, true)['token'],
            'identify' => $identify,
            'realname' => $info['realname'],
            'id' => $info['id']
        ]);
    }


    /**
     * 验证码校验
     * @param $code
     * @param string $id
     * @return bool
     * @author songxk
     */
    public function check_verify($code, $id = '')
    {
        $verify = new Verify();
        return $verify->check($code, $id);
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

    /**
     * token 生成
     * @param $id       用户id
     * @param $role     用户身份
     * @return false|string
     */
    private function getToken($id, $role)
    {
        $key = "MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAKG21GW0UttFcyu85gSMG1MJ/9zJ9VYPqm8wFlMrDR8vvEjhflvlVrzi6dhfVUbAql5IHKEEKTSNMdyJ72ZHTVcCAwEAAQ==";             // 这里是自定义的一个随机字串，应该写在config文件中的，解密时也会用，相当    于加密中常用的 盐 salt
        $token = [
            "iat" => time(),           // 签发时间
            "nbf" => time(),           // 在什么时候jwt开始生效  （这里表示生成100秒后才生效）
            "exp" => time() + 7200,    // token 过期时间
            "sub" => json_encode(array('id' => $id, 'role' => $role)),                 //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
        ];
        $jwt = JWT::encode($token, $key, "HS256"); //根据参数生成了 token
        return json_encode([
            "token" => $jwt
        ]);
    }
}