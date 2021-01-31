<?php


namespace Home\Model;


use Common\Model\BaseModel;

class SMSLogModel extends BaseModel
{
    public function __construct($table = 'sms_log')
    {
        parent::__construct($table);
    }
}