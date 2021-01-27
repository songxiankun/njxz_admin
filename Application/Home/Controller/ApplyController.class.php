<?php


namespace Home\Controller;


use Home\Model\AdminModel;
use Home\Model\BuildingModel;
use Home\Model\DevicesModel;
use Home\Model\DeviceTypeModel;
use Home\Model\EnginRoomModel;

class ApplyController extends BaseController
{
    /**
     * @var BuildingModel
     */
    private $buildMod;
    /**
     * @var AdminModel
     */
    private $adminMod;
    /**
     * @var EnginRoomModel
     */
    private $enginMod;
    /**
     * @var DevicesModel
     */
    private $deviceMod;
    /**
     * @var DeviceTypeModel
     */
    private $deviceTypeMod;

    public function __construct()
    {
        parent::__construct();
        $this->buildMod = new BuildingModel();
        $this->adminMod = new AdminModel();
        $this->enginMod = new EnginRoomModel();
        $this->deviceMod = new DevicesModel();
        $this->deviceTypeMod = new DeviceTypeModel();
    }

    /**
     * 申请表单页面
     * @author songxk
     */
    public function getBuildingAndAdminInfo()
    {
        if (IS_POST) {
            // 获取所有楼层
            $where = array(
                'mark' => 1,
            );
            $buildArray = $this->buildMod->field('id, name')->where($where)->select();

            // 查询管理人员  4 role_ids
            $admins = $this->adminMod->field('realname, id, num')
                ->where("role_ids LIKE '%4%' and mark = 1")->select();

            $data = array(
                'buildings' => $buildArray,
                'admins' => $admins
            );

            $this->ajaxReturn(message('获取楼成功', true, $data));
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 联动获取楼层信息
     * @author songxk
     */
    public function getFloors()
    {
        if (IS_POST) {
            $id = I("post.id", 0);
            // 获取所有楼层
            $where = array(
                'id' => $id,
                'mark' => 1,
            );
            $floors = $this->buildMod->field('floors')->where($where)->find()['floors'];

            $this->ajaxReturn(message("获取成功", true, ['floors' => $floors]));
        }
    }

    /**
     * 根据楼名 楼层 负责人 联动现实机房
     * @author songxk
     */
    public function getRooms()
    {
        if (IS_POST) {
            $building_id = I("post.building_id", 0);
            $floor_id = I("post.floor_id", 0);
            $adminID = I("post.admin_id", 0);

            $where = array();

            if ($adminID == session('adminId')) {
                $where = ['admin_id' => $adminID];
            }
            // 获取所有楼层
            $where = array(
                'building_id' => $building_id,
                'floor' => $floor_id,
                'mark' => 1,
            );
            // 信息查询  消息回送
            $rooms = $this->enginMod->field('id, name')->where($where)->select();
            if (empty($rooms)) {
                $this->ajaxReturn(message("暂无数据", false));
            }
            $this->ajaxReturn(message("获取成功", true, ['rooms' => $rooms]));
        }
    }

    /**
     * 根据用户id 楼名 楼层 机房编号 获取设备信息
     * @author songxk
     */
    public function getCCode()
    {
        if (IS_POST) {
            $room_id = I("post.room_id", 0);
            $adminID = I("post.admin_id", 0);

            $where = array();

            if ($adminID == session('adminId')) {
                $where = ['admin_id' => $adminID];
            }
            // 获取所有楼层
            $where = array(
                'engin_room_id' => $room_id,
                'njxz_device_type.parent_id' => 0,
                'njxz_devices.mark' => 1,
            );
            // 信息查询  消息回送
            $computerCodes = $this->deviceMod->field('njxz_devices.id, njxz_devices.num, name')
                ->join("njxz_device_type ON njxz_device_type.id = njxz_devices.device_type_id")
                ->where($where)->select();

            if (empty($computerCodes)) {
                $this->ajaxReturn(message("暂无数据", false));
            }
            $this->ajaxReturn(message("获取成功", true, ['computerCodes' => $computerCodes]));
        }
    }

    /**
     * 根据主设备id 获取所有子设备信息
     * @author songxk
     */
    public function getChildCC()
    {
        if (IS_POST) {
            $id = I("post.id", 0);
            $adminID = I("post.admin_id", 0);

            $where = array();

            if ($adminID == session('adminId')) {
                $where = ['admin_id' => $adminID];
            }
            // 获取所有楼层
            $where = array(
                'id' => $id,
                'mark' => 1
            );
            // 查询当前设备id
            $ids = $this->deviceMod->field('device_type_id')->where($where)->find()['device_type_id'];

            // 查询主设备一下的子设备
            $w = array([
                'parent_id' => $ids,
                'mark' => 1
            ]);
            $info = $this->deviceTypeMod->field('id, name')->where($w)->select();
            if (empty($info)) {
                $this->ajaxReturn(message("暂无数据", false));
            }
            $this->ajaxReturn(message("获取成功", true, ['childCC' => $info]));
        }
    }
}