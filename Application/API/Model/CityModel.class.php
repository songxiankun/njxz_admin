<?php

/**
 * 城市-模型
 */

namespace API\Model;

use Common\Model\CBaseModel;

class CityModel extends CBaseModel
{
    function __construct()
    {
        parent::__construct('city');
    }

    /**
     * 获取缓存信息
     * (non-PHPdoc)
     * @see \Common\Model\CBaseModel::getInfo()
     */
    function getInfo($id)
    {
        $info = parent::getInfo($id);
        if ($info) {
            //TODO...
        }
        return $info;
    }

    /**
     * 获取城市名称
     */
    function getCityName($cityId, $delimiter = "", $isReplace = false)
    {
        do {
            $info = $this->getInfo($cityId);
            if ($isReplace) {
                $names[] = str_replace(array("省", "市", "维吾尔", "壮族", "回族", "自治区"), "", $info['name']);
            } else {
                $names[] = $info['name'];
            }
            $cityId = $info['parent_id'];
        } while ($cityId > 1);
        $names = array_reverse($names);
        if (strpos($names[1], $names[0]) === 0) {
            unset($names[0]);
        }
        return implode($delimiter, $names);
    }

    public function getCityNameByCityId($cityId)
    {
        $city_name = $this->where(['id' => $cityId, 'is_open' => 1, 'mark' => 1])->getField('name');

        return $city_name;
    }
}