<?php


namespace Home\Model;
use Common\Model\BaseModel;

class AdminOrgModel extends BaseModel
{
    public function __construct($table = 'admin_org')
    {
        parent::__construct($table);
    }
}