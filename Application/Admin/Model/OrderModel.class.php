<?php


namespace Admin\Model;


use Common\Model\CBaseModel;

class OrderModel extends CBaseModel
{
    public function __construct($table = "order")
    {
        parent::__construct($table);
    }
}