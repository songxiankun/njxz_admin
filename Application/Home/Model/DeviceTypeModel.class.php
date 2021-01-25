<?php


namespace Home\Model;


use Common\Model\BaseModel;

class DeviceTypeModel extends BaseModel
{
    public function __construct($table = 'device_type')
    {
        parent::__construct($table);
    }
}