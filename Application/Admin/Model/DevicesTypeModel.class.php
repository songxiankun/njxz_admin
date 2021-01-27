<?php


namespace Admin\Model;

use Common\Model\CBaseModel;

class DevicesTypeModel extends CBaseModel
{
    public function __construct($table="device_type")
    {
        parent::__construct($table);
    }

    /**
     * 获取子级信息
     * @param $parentId
     * @param bool $isSon
     * @return mixed
     */
    function getChilds($parentId, $isSon=true) {
        $map = [
            'parent_id'=>$parentId,
            'mark'=>1,
        ];
        $result = $this->where($map)->order("id asc")->select();
        if($result) {
            foreach ($result as $val) {
                $id = (int)$val['id'];
                $info = $this->getInfo($id);
                if(!$info) continue;
                $childList = $this->getChilds($id,$isSon);
                if($childList) {
                    $info['children'] = $childList;
                }
                $list[] = $info;
            }
        }
        return $list;
    }

}