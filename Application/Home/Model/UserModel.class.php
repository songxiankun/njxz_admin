<?php


namespace Home\Model;


use Common\Model\BaseModel;

class UserModel extends BaseModel
{
    public function __construct($table = 'user')
    {
        parent::__construct($table);
    }
}