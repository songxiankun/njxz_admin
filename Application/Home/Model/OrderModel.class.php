<?php


namespace Home\Model;


use Common\Model\BaseModel;

class OrderModel extends BaseModel
{
    public function __construct($table = 'order')
    {
        parent::__construct($table);
    }
}