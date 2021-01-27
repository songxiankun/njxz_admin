<?php

/**
 * 配置-服务类
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
use Admin\Model\ConfigModel;
class ConfigService extends ServiceModel {
    function __construct() {
        parent::__construct();
        $this->mod = new ConfigModel();
    }

    /**
     * @return array
     */
    public function update()
    {
        $data = I("post.");
        $res = $this->mod->save($data);

        if (!$res) {
            return message("设置失败，请联系管理员", false, []);
        }

        return message("设置成功", true, []);
    }
}