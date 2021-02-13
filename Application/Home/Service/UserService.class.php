<?php


namespace Home\Service;

use Home\Model\UserModel;
use Think\Model;

class UserService extends BaseService
{
    /**
     * 获取用户消息
     * @param $uid
     * @return array
     */
    public function getInfo($uid)
    {
        $userMod = new UserModel();
        $info = $userMod->field('id, avatar, job_num, mobile, email, note')
            ->where([
                'mark' => 1,
                'id' => $uid
            ])->find();

        if ($info) {
            $info['num'] = $info['job_num'];
            return message("获取成功", true, $info);
        }

        return message("获取失败", false, []);
    }


    /**
     * 更新用户信息
     * @param $id
     * @return array
     * @author songxk
     */
    public function edit($id)
    {
        $data = I("post.");  // 获取所有信息
        $userMod = new UserModel();

        $data['id'] = $id;
        if (isset($data['id']) && $data['id']) {
            $count = $userMod->where(['mobile' => $data['mobile']])->count();
            if ($count > 0) {
                return message("手机号码已注册", false, []);
            }
        }

        if (isset($data['token']) && $data['token'])
            unset($data['token']);

        unset($data['file']);

        $res = $userMod->save($data);

        if (!$res) {
            return message("更新失败", false, []);
        }
        return message("保存成功", true, []);
    }

    /**
     * 更新密码
     * @param $id_
     * @return array
     */
    public function update($id_)
    {
        $data = I("post.");
        $id = $id_;
        $old_password = $data['old_password'];
        $new_password = $data['new_password'];
        $again_password = $data['again_password'];

        if ($new_password != $again_password) {
            return message("密码与确认密码不一致", false, []);
        }

        if ($new_password == $old_password) {
            return message("新密码和旧密码不能一致", false, []);
        }

        $userMod = new UserModel();
        $info = $userMod->field('password')->where(['id' => $id, 'mark' => 1])->find();

        if (!$info) {
            return message("非法操作", false, []);
        }

        $basePass = $info['password'];

        if ($basePass == md5(md5($old_password))) {
            $info['password'] = md5(md5($new_password));
            $res = $userMod->where(['id' => $id])->save($info);
            if ($res) {
                return message("更新成功", true, []);
            } else {
                return message("更新失败", false, []);
            }
        } else {
            return message("旧密码错误", false, []);
        }
    }

    /**
     * 用户注册
     * @return array
     */
    public function doRegister()
    {
        $data = I("post.");
        // 设置status 为 0
        $data['status'] = 0;
        $userMod = new UserModel();

        // 检测是否已经注册
        $count = $userMod->where(['job_num' => $data['job_num']])->count();
        if ($count) {
            return message("此工号已被注册，如忘记密码，请更改密码！！！", false, []);
        }

        $count = $userMod->where(['email' => $data['email']])->count();
        if ($count) {
            return message("此邮箱已被绑定，请更改邮箱注册！！！", false, []);
        }

        // 密码加密
        if (isset($data['password']) && $data['password']) {
            $data['password'] = \Zeus::getPassWord($data['password']);
        }

        // 注册时间
        $data['add_time'] = time();
        $res = $userMod->add($data);
        if ($res) {
            // 发送邮件
            $this->sendEmail([
                'toAddress' => $data['email'],
                'toName' => $data['nickname'],
                'subject' => '南京晓庄实验室·账号激活邮件',
                'htmlData' => '<h1><a href="'.C('api').'/User/status?uid='.$userMod->getLastInsID().'">点我进行账号激活</a></h1>',
                'data' => C("api").'/User/status?uid='.$userMod->getLastInsID()
            ]);
            return message("注册成功，请登录邮箱：" . $data['email'] . "进行账号激活!!!", true, []);
        }
        return message("注册失败,请联系管理员", false, []);
    }

    /**
     * 注册账号激活
     */
    public function updateStatus()
    {
        $id = I("get.uid");
        if ($id == '') {
            return message("非法请求", false, []);
        }
        $userMod = new UserModel();

        $data= $userMod->where(['id' => $id])->find();

        if (!$data) {
            return message("不存在该账号！！！", false, []);
        }
        if ($data['status'] == 1) {
            return message("账号已激活，请勿重复激活", false, []);
        }

        $res = $userMod->save(['id' => $id, 'status' => 1]);
        if ($res) {
            return message("激活成功", true, []);
        }
        return message('激活失败，请联系管理员!!!', false, []);
    }
}