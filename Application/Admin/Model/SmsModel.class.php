<?php
/**
 * Created by PhpStorm.
 */

namespace Admin\Model;


use Common\Model\CBaseModel;

class SmsModel extends CBaseModel
{
    public function __construct()
    {
        parent::__construct('sms_log');
    }

    public function getSmsLog($params, $fields = [])
    {
        $page = $params['page'];
        $limit = $params['limit'];
        $condition = [
            'mark' => 1,
        ];
        if ($params['mobile']) {
            $condition['mobile'] = $params['mobile'];
        }
        $count = $this->field($fields)->where($condition)->count();
        $result = $this->field($fields)->where($condition)->page($page, $limit)->order('id DESC')->select();

        return [$count, $result];
    }
}
