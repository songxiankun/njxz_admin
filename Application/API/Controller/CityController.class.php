<?php

/**
 * 城市-控制器
 */
namespace API\Controller;
use API\Model\CityModel;
use API\Service\CityService;
class CityController extends APIBaseController {
    function __construct() {
        parent::__construct();
        $this->mod = new CityModel();
        $this->service = new CityService();
    }
    
    /**
     * 获取开放城市列表
     * 
     * @author zongjl
     * @date 2019-01-17
     */
    function getCityList() {
        $result = $this->service->getCityList();
        $this->jsonReturn($result);
    }

    /**
     * @brief 根据父级ID获取城市
     */
    public function getCityByParentId()
    {
        $result = $this->service->getCityByParentId($this->req);
        $this->jsonReturn($result);
    }
    
}