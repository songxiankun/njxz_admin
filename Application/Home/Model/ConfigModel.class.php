<?php


namespace Home\Model;

use Common\Model\BaseModel;

class ConfigModel extends BaseModel
{
    public function __construct($table = 'config')
    {
        parent::__construct($table);
    }
}