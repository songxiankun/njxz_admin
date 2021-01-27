<?php


namespace Home\Model;


use Common\Model\BaseModel;

class RepairApplicationModel extends BaseModel
{
    public function __construct($table = 'repair_application')
    {
        parent::__construct($table);
    }
}