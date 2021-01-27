<?php

/**
 * 城市-服务类
 */

namespace API\Service;

use API\Model\CityModel;

class CityService extends APIServiceModel
{
    function __construct()
    {
        parent::__construct();
        $this->mod = new CityModel();
    }

    /**
     * 获取开放城市列表
     */
    function getCityList()
    {
        $map = [
            'query' => [
                'is_open' => 1,
            ],
            'sort' => 'id ASC',
        ];
        $result = $this->getData($map, function ($info) {
            $data = [
                'city_id' => $info['id'],
                'city_name' => $info['name'],
                'city_code' => $info['citycode'],//城市编码
                'city_adcode' => $info['adcode'],//地理编码
            ];
            return $data;
        });
        return message(MESSAGE_OK, true, $result);
    }

    public function getCityByParentId($param)
    {
        $city_id = !empty($param['city_id']) ? intval($param['city_id']) : 1;
        $map = [
            'query' => [
                'parent_id' => $city_id,
                'is_open' => 1,
            ],
            'sort' => 'id ASC',
        ];
        $result = $this->getData($map, function ($info) {
            $data = [
                'id' => $info['id'],
                'name' => $info['name'],
            ];
            return $data;
        });

        return message(MESSAGE_OK, true, $result);
    }
}