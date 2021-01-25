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
use Think\Model;

class RepairApplicationService extends Model
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
    public function getList() {
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

        foreach ($result as $key => $value)
        {
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

            $result[$key]['org'] = $organize_name . " << " .$dept_name;
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
                    $str .= $deviceName. " : " . $v['content'] . "; ";
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
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $result
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
        $adminMod =new AdminModel();
        $adminID = $adminMod->field('id')->where(['id' => $uid, 'mark' => 1])->find();
        if (!$adminID) {
            return message("非法操作", false, []);
        }

        $id = I('post.id');

        $info = $this->mod->where([
            'mark' => 1,
            'admin_id' => $adminID['id'],
            'id'    => $id
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
}