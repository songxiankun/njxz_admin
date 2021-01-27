<?php


namespace Home\Model;


use Common\Model\BaseModel;

class DevicesModel extends BaseModel
{
    public function __construct($table = 'devices')
    {
        parent::__construct($table);
    }
}