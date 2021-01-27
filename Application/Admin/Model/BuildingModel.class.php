<?php


namespace Admin\Model;

use Common\Model\CBaseModel;

class BuildingModel extends CBaseModel
{
    public function __construct($table="building")
    {
        parent::__construct($table);
    }

    public function getInfo($id, $flag = false)
    {
        $info = $this->getFuncCache("info", $id);
        if($info) {
            //添加时间
            if(isset($info['add_time']) && $info['add_time']) {
                $info['format_add_time'] = date('Y-m-d H:i:s',$info['add_time']);
            }

            //更新时间
            if(isset($info['upd_time']) && $info['upd_time']) {
                $info['format_upd_time'] = date('Y-m-d H:i:s',$info['upd_time']);
            }

            //获取系统操作人信息
            if($flag) {
                //添加人
                if($info['add_user']) {
                    $info['format_add_user'] = $this->getSystemAdminName($info['add_user']);
                }

                //更新人
                if($info['upd_user']) {
                    $info['format_upd_user'] = $this->getSystemAdminName($info['upd_user']);
                }

            }

        }
        return $info;
    }
}