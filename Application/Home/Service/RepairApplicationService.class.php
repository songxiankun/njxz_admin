<?php


namespace Home\Service;

use Home\Model\AdminDepModel;
use Home\Model\AdminModel;
use Home\Model\AdminOrgModel;
use Home\Model\BuildingModel;
use Home\Model\DevicesModel;
use Home\Model\DeviceTypeModel;
use Home\Model\EnginRoomModel;
use Home\Model\RepairApplicationModel;
use Home\Model\SMSLogModel;

class RepairApplicationService extends BaseService
{
    /**
     * @var RepairApplicationModel
     */
    private $mod;

    public function __construct()
    {
        $this->mod = new RepairApplicationModel();
    }

    /**
     * 根据html传递的t_id 判断当前请求类型
     * 1 待审核 2 未通过 3 已通过
     * @author songxk
     */
    public function getList()
    {
        // t_id=1&uid=5&page=1&limit=15
        $t_id = I("get.t_id");
        $id = I("get.uid");
        $page = I("get.page") == '' ? 1 : I("get.page");
        $limit = I("post.limit") == '' ? 15 : I("post.limit");

        if ($id == "")
            return message('非法请求', false, []);

        $where = [
            'mark' => 1,
            'admin_id' => $id
        ];

        if ($t_id == 1) {
            $where['status'] = 1;
        } elseif ($t_id == 2) {
            $where['status'] = 2;
        } elseif ($t_id == 3) {
            $where['status'] = 3;
        }

        $result = $this->mod
            ->where($where)->order("id desc")
            ->page($page, $limit)->select();

        foreach ($result as $key => $value) {
            $organize_name = "";
            $dept_name = "";
            // 获取组织name
            if (isset($value['organize_id']) && $value['organize_id']) {
                $orgMod = new AdminOrgModel();
                $organize_name = $orgMod->field('name')
                    ->where(['id' => $value['organize_id'], 'mark' => 1])
                    ->find()['name'];
            }

            // 获取部门名字
            if (isset($value['department_id']) && $value['department_id']) {
                $deptMod = new AdminDepModel();
                $dept_name = $deptMod->field('name')
                    ->where(['id' => $value['department_id'], 'mark' => 1])
                    ->find()['name'];
            }

            $result[$key]['org'] = $organize_name . " << " . $dept_name;
            // 获取所在位置building_id
            if (isset($value['building_id']) && $value['building_id']) {
                $bulidMod = new BuildingModel();
                $result[$key]['build_name'] = $bulidMod->field('name')
                    ->where(['id' => $value['building_id'], 'mark' => 1])
                    ->find()['name'];
            }

            // 获取机房信息
            if (isset($value['engin_room_id']) && $value['engin_room_id']) {
                $enginMod = new EnginRoomModel();
                $result[$key]['engin_room'] = $enginMod->field('name')
                    ->where(['id' => $value['engin_room_id'], 'mark' => 1])
                    ->find()['name'];
            }

            // 获取设备详情
            if (isset($value['device_id']) && $value['device_id']) {
                $deviceMod = new DevicesModel();
                $result[$key]['device_num'] = $deviceMod->field('num')
                    ->where(['id' => $value['device_id'], 'mark' => 1])
                    ->find()['num'];
            }

            // 申请人名字
            if (isset($value['admin_id']) && $value['admin_id']) {
                $adminMod = new AdminModel();
                $result[$key]['admin_name'] = $adminMod->field('realname')
                    ->where(['id' => $value['admin_id'], 'mark' => 1])
                    ->find()['realname'];
            }

            // images 处理
            if (isset($value['images']) && $value['images']) {
                $imageArr = json_decode($value['images']);
                $result[$key]['images'] = array();
                foreach ($imageArr as $k => $image) {
                    $result[$key]['images'][$k] = $image;
                }
            }

            // device detail 设别详情
            if (isset($value['device_detail']) && $value['device_detail']) {
                $deviceJson = json_decode($value['device_detail'], true)['parents'][0]['sub'];
                $deviceTypeMod = new DeviceTypeModel();
                $str = "";
                foreach ($deviceJson as $k => $v) {
                    $deviceName = $deviceTypeMod->field('name')
                        ->where(['id' => $v['id'], 'mark' => 1])
                        ->find()['name'];
                    $str .= $deviceName . " : " . $v['content'] . "; ";
                }
                $result[$key]['device'] = $str;
            }

            // upd_user 更新人员
            if (isset($value['upd_user']) && $value['upd_user']) {
                $adminMod = new AdminModel();
                $result[$key]['upd_name'] = $adminMod->field('realname')
                    ->where(['id' => $value['upd_user'], 'mark' => 1])
                    ->find()['realname'];
            }

            // 添加时间
            if (isset($value['add_time']) && $value['add_time']) {
                $result[$key]['format_add_time'] = date("Y-m-d H:i:s", $value['add_time']);
            }

            // 更新时间
            if (isset($value['upd_time']) && $value['upd_time']) {
                $result[$key]['format_upd_time'] = date("Y-m-d H:i:s", $value['upd_time']);
            }
        }

        $count = $this->mod->where($where)->count();

        return array(
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $result
        );
    }

    /**
     * 根据用户id和维修申请id更新数据
     * @return array
     */
    public function deleteById()
    {
        // 查询当前用户信息 获取用户id
        $uid = I("post.uid");
        $adminMod = new AdminModel();
        $adminID = $adminMod->field('id')->where(['id' => $uid, 'mark' => 1])->find();
        if (!$adminID) {
            return message("非法操作", false, []);
        }

        $id = I('post.id');

        $info = $this->mod->where([
            'mark' => 1,
            'admin_id' => $adminID['id'],
            'id' => $id
        ])->find();

        if (!$info) {
            return message("非法操作", false, []);
        }

        $info['mark'] = 0;

        $ret = $this->mod->where(['id' => $id])->save($info);

        if ($ret === false) {
            return message("更新数据失败", false, []);
        }

        return message("更新成功", true, []);
    }

    /**
     * 申请表单提交
     * @return array
     */
    public function submit()
    {
        $data = I("post.");
        if (isset($data['token']) && $data['token']) {
            $tokenData = $this->dataToken($data['token']);
            if (!$tokenData['success']) {
                return $tokenData;
            }
            // 数据处理
            $userInfo = $tokenData['data'];
            $data['organize_id'] = $userInfo['organization_id'];
            $data['department_id'] = $userInfo['department_id'];
            $data['admin_id'] = $userInfo['id'];

            unset($data['token']);
        }

        if (isset($data['images']) && $data['images']) {
            $data['images'] = htmlspecialchars_decode($data['images']);
        }

        if (isset($data['device_detail']) && $data['device_detail']) {        // images 有值
            $data['device_detail'] = htmlspecialchars_decode($data['device_detail']);
        }

        $data['add_time'] = time();

        $res = $this->mod->add($data);

        if (!$res)
            return message('申请表单写入失败：' . $this->mod->getError(), false, []);
        // 写入成功 发送邮件给审核人
        // 获取信息
        $adminMod = new AdminModel();
        $recv_email = $adminMod->field('id, email, realname')->where([
            'id' => $data['upd_user'],
            'mark' => 1
        ])->find();

        if (!empty($recv_email)) {
            $res = $this->sendEmail([
                'toAddress' => $recv_email['email'],
                'toName' => $recv_email['realname'],
                'subject' => '您有新的审核订单，请及时审核~~',
                'htmlData' =>
                    '<h1>请点击下面链接进行订单审核</h1><br>http://home.njxzc.edu.cn/page/table/application/update_application.html?uid=' . $recv_email['id'] . "&rid=" . $this->mod->getLastInsID(),
                'data' =>
                    '请点击下面链接进行订单审核: http://home.njxzc.edu.cn/page/table/application/update_application.html?uid=' . $recv_email['id'] . "&rid=" . $this->mod->getLastInsID()]);
            // 写入日志 到sms_log
            $datas = array(
                'type' => 2,
                'content' => '<h1>请点击下面链接进行订单审核</h1><br>http://home.njxzc.edu.cn/page/table/application/update_application.html?uid=' . $recv_email['id'] . "&rid=" . $this->mod->getLastInsID(),
                'mail' => $recv_email['email'],
                'sender_id' => $data['admin_id'],
                'add_time' => time(),
            );
            // 是否发送成功
            if ($res['success'] == true) {
                $datas['status'] = 1;
            } else {
                $datas['status'] = 2;
            }
            $datas['msg'] = $res['msg'];

            // 日志入库
            $smsLog = new SMSLogModel();
            $smsLog->add($datas);
        }
        return message('申请成功', true, []);
    }

    /**
     * 获取订单信息
     * uid 申请人
     * rid 订单id
     */
    public function getApplyInfo()
    {
        $data = I("post.");
        $map = array('mark' => 1);
        // 审核人id
        if (isset($data['uid']) && $data['uid']) {
            $map['upd_user'] = $data['uid'];
        }

        // 订单id
        if (isset($data['rid']) && $data['rid']) {
            $map['id'] = $data['rid'];
        }

        // 查询信息
        $repairInfo = $this->mod->where($map)->find();

        if (empty($repairInfo)) {
            return message("请求失败，此订单不存在，请核实。申请ID为：" . $data['rid'], false, []);
        }

        // 查询是否已经审核完毕
        if ($repairInfo['status'] != 1) {
            return message("该申请已审核，请勿重复审核", false, []);
        }

        // 信息返回
        $repairInfo['status'] = "待审核";
        // 院校 系别
        $orgMod = new AdminOrgModel();
        $deptMod = new AdminDepModel();
        $repairInfo['deptName'] = $deptMod->field('name')
            ->where(['mark' => 1, 'id' => $repairInfo['department_id']])->find()['name'];
        $repairInfo['orgName'] = $orgMod->field('name')
            ->where(['mark' => 1, 'id' => $repairInfo['organize_id']])->find()['name'];

        // 楼名 教室名
        $buildMod = new BuildingModel();
        $enginMod = new EnginRoomModel();

        $repairInfo['buildName'] = $buildMod->field('name')
            ->where(['mark' => 1, 'id' => $repairInfo['building_id']])->find()['name'];
        $enginRoom = $enginMod->field('name, num')
            ->where(['mark' => 1, 'id' => $repairInfo['engin_room_id']])->find();

        if (isset($enginRoom) && $enginRoom) {
            $repairInfo['enginRoom'] = "机房 " . $enginRoom['num'] . "-" . $enginRoom['name'];
        }

        // 设备名
        $deviceName = new DevicesModel();
        $deviceName = $deviceName->field('device_name, num')
            ->where(['mark' => 1, 'id' => $repairInfo['device_id']])->find();
        if (isset($deviceName) && $deviceName) {
            $repairInfo['deviceName'] = $deviceName['num'] . '-' . $deviceName['device_name'];
        }

        // 审核人信息
        $adminMod = new AdminModel();
        $repairInfo['updName'] = $adminMod->field('realname')->where(['mark' => 1, 'id' => $repairInfo['upd_user']])->find()['realname'];
        $repairInfo['adminName'] = $adminMod->field('realname')->where(['mark' => 1, 'id' => $repairInfo['admin_id']])->find()['realname'];

        // deviceDetail images video
        $device_detail = array();
        if (isset($repairInfo['device_detail']) && $repairInfo['device_detail']) {
            $deviceArr = json_decode($repairInfo['device_detail'], true);
            $deviceMod = new DevicesModel();
            if ($deviceArr['parents']['total'] == 0) // 只有主设备
            {
                $name = $deviceMod->field("device_name")->where(['mark' => 1, 'id' => $deviceArr['parents']['id']])->find()['device_name'];
                $device_detail[0] = "[" . $name . "] :" . $deviceArr['parents']['content'];
            } else {  // 含有子设备
                $child = $deviceArr['parents']['sub'];
                for ($i = 0; $i < $deviceArr['parents']['total']; $i++) {
                    $name_ =  $deviceMod->field("device_name")->where(['mark' => 1, 'id' => $child['id']])->find()['device_name'];
                    $device_detail[$i] = "[" . $name_ . "] :" . $deviceArr['parents']['content'];
                }
            }
        }

        $repairInfo['device_detail'] = $device_detail;

        if (isset($repairInfo['images']) && $repairInfo['images']) {
            $repairInfo['images'] = json_decode($repairInfo['images'], true);
        }

        if (isset($repairInfo['video']) && $repairInfo['video']) {
            $repairInfo['video'] = json_decode($repairInfo['video'], true);
        }

        if (isset($repairInfo['add_time']) && $repairInfo['add_time']) {
            $repairInfo['add_time'] = date("Y-m-d H:i:s", $repairInfo['add_time']);
        }

        return message('请求成功', true, $repairInfo);
    }

    public function update()
    {
        $data = I("post.");

        // 更新到数据库
        $res = $this->mod->save($data);

        if (!$res) {
            return message('更新失败', false, []);
        }

        // 获取信息 发送消息给申请用户
        $info = $this->mod->where(['id' => $data['id'], 'mark' => 1])->find();

        if (!empty($info)) {
            if ($data['status'] == 2) // 审核未通过
            {
                // 获取申请人email
                $adminMod = new AdminModel();
                $email = $adminMod->field('email, realname')->where(['id' => $info['admin_id'], 'mark' => 1])->find();
                if (!empty($email)) {
                    $email['email'] = $email['email'] == null ? "1281541477@qq.com" : $email['email'];
                    $res = $this->sendEmail([
                        'toAddress' => $email['email'],
                        'toName' => $email['realname'],
                        'subject' => '您的审核订单已审核，请及时查看~~',
                        'htmlData' =>
                            '<h1>请点击下面链接进行订单审核</h1><br>http://home.njxzc.edu.cn',
                        'data' =>
                            '请点击下面链接进行订单审核: http://home.njxzc.edu.cn/page/table/application/update_application.html']);
                    // 写入日志 到sms_log
                    $datas = array(
                        'type' => 2,
                        'content' => '<h1>请点击下面链接进行订单审核</h1><br>http://home.njxzc.edu.cn',
                        'mail' => $email['email'],
                        'sender_id' => $info['upd_user'],
                        'add_time' => time(),
                    );
                    // 是否发送成功
                    if ($res['success'] == true) {
                        $datas['status'] = 1;
                    } else {
                        $datas['status'] = 2;
                    }
                    $datas['msg'] = $res['msg'];

                    // 日志入库
                    $smsLog = new SMSLogModel();
                    $smsLog->add($datas);
                }
            } else {  // TODO 审核通过生成订单 发送信息

            }
        }

        return message('审核成功', true, []);
    }
}