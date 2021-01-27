<?php


namespace Admin\Model;


use Common\Model\CBaseModel;

/**
 * Class RepairApplyModel  模型
 * @package Admin\Model
 */
class RepairApplyModel extends CBaseModel
{
    public function __construct($table = 'repair_application')
    {
        parent::__construct($table);
    }
}