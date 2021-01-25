<?php


namespace Admin\Widget;


use Admin\Model\DevicesTypeModel;
use Think\Controller;

/**
 * 设备名称挂件表
 * Class DevicesTypeWidget
 * @package Admin\Widget
 */
class DevicesTypeWidget extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 选择设备名称
     * @param $param
     * @param $selectId
     */
    public function select($param,$selectId)
    {
        $arr = explode('|', $param);

        //参数 id|1|设备名称|name|id
        $idStr = $arr[0];
        $isV = $arr[1];
        $msg = $arr[2];
        $show_name = $arr[3];
        $show_value = $arr[4];

        $deviceTypeModel = new DevicesTypeModel();
        $deviceTypeList = $deviceTypeModel->field('id,name')
            ->where(['parent_id' => 0, 'mark' => 1])->select();

        $this->assign('idStr', $idStr);
        $this->assign('isV', $isV);
        $this->assign('msg', $msg);
        $this->assign('show_name', $show_name);
        $this->assign('show_value', $show_value);
        $this->assign('deviceTypeList', $deviceTypeList);
        $this->assign("selectId", $selectId);
        $this->display("DevicesType:DevicesType.select");
    }
}