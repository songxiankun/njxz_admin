<?php
namespace Home\Model;
use Common\Model\BaseModel;

class AdminModel extends BaseModel
{
    public function __construct($table = 'admin')
    {
        parent::__construct($table);
    }


}