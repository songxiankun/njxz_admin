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
     * @author songxk
     */
    public function edit($id)
    {
        $data = I("post.");  // 获取所有信息
        $userMod = new UserModel();

        $data['id'] = $id;
        if (isset($data['id']) && $data['id'])
        {
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
            return message( "更新失败", false, []);
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
            }
            else
            {
                return message("更新失败", false, []);
            }
        } else {
            return message("旧密码错误", false, []);
        }
    }
}