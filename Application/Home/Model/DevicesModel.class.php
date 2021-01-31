<?php


namespace Home\Model;


use Common\Model\BaseModel;

class DevicesModel extends BaseModel
{
    public function __construct($table = 'devices')
    {
        parent::__construct($table);
    }

    /**
     * 获取设备消息根据编号
     * @param $num
     * @return array
     * @author kunkun
     */
    public function getInfoByNums($num)
    {
        $data = $this->where(['mark' => 1, 'num' => $num])->find();

        if (is_null($data)) {
            return message('查询失败，不存在该编号为:[' . $num . ']的设备', false, []);
        }
        /**
        'id' => string '1' (length=1)
        'origin_num' => string '' (length=0)
        'num' => string '00147577' (length=8)
        'device_name' => string '微机原理与接口教学实验系统' (length=39)
        'type_name' => string 'Dais-86PRO' (length=10)
        'norm' => string '*' (length=1)
        'count' => string '1' (length=1)
        'money' => string '325000' (length=6)
        'achieve_time' => string '1523980800' (length=10)
        'department_id' => null
        'department_name' => string '信息工程学院/微机接口技术与单片机原理实验室' (length=64)
        'admin_id' => null
        'admin_name' => string '邵玲燕' (length=9)
        'address' => string '方山校区 工科楼南403 微机接口技术与单片机原理实验室' (length=74)
        'add_time' => string '1611057483' (length=10)
        'upd_time' => null
        'add_user' => string '1' (length=1)
        'mark' => string '1' (length=1)
         */
        // 首先机房信息 工科楼 1楼 101机房
        if (isset($data['address']) && $data['address']) {
            $arr = explode(" ", $data['address']);
            /**
            0 => string '方山校区' (length=12)
            1 => string '工科楼南403' (length=15)
            2 => string '微机接口技术与单片机原理实验室' (length=45)
             */
            if (sizeof($arr) == 3) { // 处理工科楼
                if (preg_match('/[0-9]/', $arr[1], $matches, PREG_OFFSET_CAPTURE)) {
                    $bulidMod = new BuildingModel();
                    $name = substr($arr[1], 0, $matches[0][1]);
                    $building_id = $bulidMod->field('id')->where([
                        'name'  => $name,
                        'mark'  => 1
                    ])->find();

                    // 获取building_id
                    if (isset($building_id) && $building_id) {
                        $data['building_id'] = $building_id['id'];
                        $data['building_name'] = $name;
                    }

                    // 获取楼层
                    $data['floor'] = $matches[0][0];

                    // 机房编号
                    $data['room_num'] = '机房' . strstr($arr[1], $matches[0][0]) . '-' . $arr[2];

                    var_dump($data);
                    die();
                }
            }
            // 处理鹤琴楼
            die();

           // var_dump($arr);die();
        }


    }
}