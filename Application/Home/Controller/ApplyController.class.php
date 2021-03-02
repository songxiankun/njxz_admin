<?php


namespace Home\Controller;


use Home\Model\AdminModel;
use Home\Model\BuildingModel;
use Home\Model\DevicesModel;
use Home\Model\DeviceTypeModel;
use Home\Model\EnginRoomModel;
use Home\Service\RepairApplicationService;

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
    /**
     * @var RepairApplicationService
     */
    private $repairApplyService;

    public function __construct()
    {
        parent::__construct();
        $this->buildMod = new BuildingModel();
        $this->adminMod = new AdminModel();
        $this->enginMod = new EnginRoomModel();
        $this->deviceMod = new DevicesModel();
        $this->deviceTypeMod = new DeviceTypeModel();
        $this->repairApplyService = new RepairApplicationService();
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

            $floors = $this->buildMod->field('floors')->where($where)->find();

            if (empty($floors)) {
                $this->ajaxReturn(message("获取失败", false, []));
            }
            $this->ajaxReturn(message("获取成功", true, ['floors' => $floors['floors']]));
        }
        $this->ajaxReturn(message('非法请求', false, []));
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
            $adminID = $this->dataToken(I('token', true))['data']['data']['id'];

            // 获取所有楼层
            $where = array(
                'admin_id' => $adminID,
                'building_id' => $building_id,
                'floor' => $floor_id,
                'mark' => 1,
            );
            // 信息查询  消息回送
            $rooms = $this->enginMod->field('id, num')->order('num asc')->where($where)->group('num')->select();

            if (empty($rooms)) {
                $this->ajaxReturn(message("暂无数据", false));
            }
            $this->ajaxReturn(message("获取成功", true, ['rooms' => $rooms]));
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 根据用户id 楼名 楼层 机房编号 获取设备信息
     * @author songxk
     */
    public function getCCode()
    {
        if (IS_POST) {
            $room_id = I("post.room_id", 0);
            $building_id = I("post.building_id", 0);

            if ($room_id) {
                $res = $this->enginMod->field('num')->
                where(['mark' => 1, 'id' => $room_id, 'building_id' => $building_id])->find();

                if (empty($res)) {
                    $this->ajaxReturn(message("此机房不存在", false, []));
                }

                $num = $res['num'];
            } else {
                $this->ajaxReturn(message("机房编号为空", false, []));
            }

            $admin_name = $this->dataToken(I('token', true))['data']['data']['realname'];
            // 获取所有楼层
            $where = array(
                'admin_name' => ['like', '%' . $admin_name . '%'],
                'address' => ['LIKE', '%' . $num . '%'],
                'mark' => 1,
            );
            // 信息查询  消息回送
            $computerDatas = $this->deviceMod->field('id, num, device_name')
                ->where($where)->select();

            if (empty($computerDatas)) {
                $this->ajaxReturn(message("暂无数据", false));
            }
            // 数据返回
            $this->ajaxReturn(message("获取成功", true, ['computerData' => $computerDatas]));
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 根据主设备id 获取所有子设备信息
     * @author songxk
     */
    public function getChildCC()
    {
        if (IS_POST) {
            $id = I("post.id", 0);
            $admin_name = $this->dataToken(I('token', true))['data']['data']['realname'];
            // 获取所有楼层
            $where = array(
                'id' => $id,
                'admin_name' => ['like', '%' . $admin_name . '%'],
                'mark' => 1,
            );
            // 信息查询
            $parent_name = $this->deviceMod->field('device_name')
                ->where($where)->find();

            // 循环遍历信息 获取子节点
            if (!empty($parent_name)) {
                $name = mb_substr($parent_name['device_name'], 0, 2);

                $parent_id = $this->deviceTypeMod->field('id')->where([
                    'name' => array('LIKE', "%" . $name . '%'),
                    'parent_id' => 0,
                    'mark' => 1
                ])->find();

                // 查询到当前设备的信息 进行子设备查询
                if (!empty($parent_id)) {
                    $childData = $this->deviceTypeMod->field('id, name')->where([
                        'mark' => '1',
                        'parent_id' => $parent_id['id']
                    ])->select();

                    if (!empty($childData)) {
                        $this->ajaxReturn(message("获取成功", true, ['childCC' => $childData]));
                    }
                } else {
                    // code == 100 为当前设备无子设备
                    $this->ajaxReturn(message("暂无子设备数据", false, [], 100));
                }
            }
            else
            {
                $this->ajaxReturn(message('获取数据失败', false, []));
            }
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 根据设备唯一编号获取设备详情信息
     * @author kunkun
     */
    public function getInfoByNum()
    {
        if (IS_POST) {
            $num = I('post.num', 0);

            if ($num == 0)
                $this->ajaxReturn(message('参数错误', false, []));
            $res = $this->deviceMod->getInfoByNums($num);
            $this->ajaxReturn(message('获取成功', true, $res));
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }

    /**
     * 提交申请---生成申请订单
     * @author songxk
     */
    public function submit()
    {
        if (IS_POST) {
            $res = $this->repairApplyService->submit();
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn(message('非法请求', false, []));
    }
}