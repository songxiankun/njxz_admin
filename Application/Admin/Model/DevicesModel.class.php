<?php


namespace Admin\Model;

use Common\Model\CBaseModel;
use Think\Db;
use Zeus;

class DevicesModel extends CBaseModel
{
    public function __construct($table = "devices")
    {
        parent::__construct($table);
    }

    /**
     * @brief 根据id获取缓存信息
     * @param $id
     * @param false $flag
     * @return mixed
     */
    public function getInfo($id, $flag = false)
    {
        $info = $this->getFuncCache("info", $id);
        // 数据格式化
        if ($info) {
            //添加时间
            if (isset($info['add_time']) && $info['add_time']) {
                $info['format_add_time'] = date('Y-m-d H:i:s', $info['add_time']);
            }
            //更新时间
            if (isset($info['upd_time']) && $info['upd_time']) {
                $info['format_upd_time'] = date('Y-m-d H:i:s', $info['upd_time']);
            }

            // money格式化
            $info['money'] = (isset($info['money']) && $info['money']) ?
                Zeus::formatToYuan($info['money']) : $info['money'];

            // achieve_time格式化
            $info['format_achieve_time'] = (isset($info['achieve_time']) && $info['achieve_time']) ?
                date('Y-m-d H:i:s', $info['achieve_time']) : $info['achieve_time'];

//            // 'device_type_id' => string '1' (length=1)
//            $deviceTypeModel = new DevicesTypeModel();
//            $info['device_type_name'] = (isset($info['device_type_id']) && $info['device_type_id']) ?
//                $deviceTypeModel->getInfo($info['device_type_id'])['name'] : $info['device_type_id'];

//            //  'department_id' => string '1' (length=1)
//            $deptModel = new AdminDepModel();
//            $info['department_name'] = (isset($info['department_id']) && $info['department_id']) ?
//                $deptModel->getInfo($info['department_id'])['name'] : $info['department_id'];

            //  'admin_id' => string '1' (length=1)
//            $adminModel = new AdminModel();
//            $info['admin_name'] = (isset($info['admin_id']) && $info['admin_id']) ?
//                 $adminModel->getInfo($info['admin_id'])['realname'] : $info['admin_id'];

            //  'engin_room_id' => string '1' (length=1)
//            $enginRoomModel = new EnginRoomModel();
//            $info['engin_room_name'] = (isset($info['engin_room_id']) && $info['engin_room_id']) ?
//                $enginRoomModel->getInfo($info['engin_room_id'])['name'] : $info['engin_room_id'];

            //获取系统操作人信息
            if ($flag) {
                //添加人
                if ($info['add_user']) {
                    $info['format_add_user'] = $this->getSystemAdminName($info['add_user']);
                }

                //更新人
                if ($info['upd_user']) {
                    $info['format_upd_user'] = $this->getSystemAdminName($info['upd_user']);
                }
            }
        }
        return $info;
    }

    /**
     * 删除
     * @param $id
     * @param false $is_sql
     * @return bool|false|int|mixed|string
     */
    public function drop($id, $is_sql = false)
    {
        $result = $this->where("id={$id}")->setField('mark','0');
        if($is_sql) echo $this->_sql();

        // 删除成功删除缓存
        if($result!==false) {
            //删除成功
            $this->_cacheDelete($id);
        }
        return $result;
    }

    /**
     * @brief 批量插入
     * @param $dataList
     * @param array $options
     * @param false $replace
     * @return false|mixed|string
     */
    public function addAll($dataList, $options = array(), $replace = false)
    {
        if(empty($dataList)) {
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }
        // 数据处理addAll
        foreach ($dataList as $key=>$data){
            $dataList[$key] = $this->_facade($data);
        }
        // 分析表达式
        $options =  $this->_parseOptions($options);
        // 写入数据到数据库
        $result = $this->db->insertAll($dataList,$options,$replace);
//        if(false !== $result ) {
//            $insertId   =   $this->getLastInsID();
//            if($insertId) {
//                return $insertId;
//            }
//        }
        return $result;
    }
}