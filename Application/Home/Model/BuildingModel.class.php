<?php


namespace Home\Model;


use Common\Model\BaseModel;

class BuildingModel extends BaseModel
{
    public function __construct($table = 'building')
    {
        parent::__construct($table);
    }
}