<?php


namespace Admin\Service;

use Admin\Model\DevicesTypeModel;
use Admin\Model\ServiceModel;

class DevicesTypeService extends ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new DevicesTypeModel();
    }

    public function getList($map = array(), $sort = "id desc")
    {
        $list = $this->mod->getChilds(0);
        return message("操作成功",1,$list);
    }
}