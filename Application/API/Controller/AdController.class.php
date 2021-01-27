<?php
/**
 * Created by PhpStorm.
 */

namespace API\Controller;

use API\Service\AdService;

class AdController extends APIBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new AdService();
    }

    /**
     * @brief 获取首页广告Banner
     */
    public function getIndexAd()
    {
        $result = $this->service->getIndexAd();
        $this->jsonReturn($result);
    }
}
