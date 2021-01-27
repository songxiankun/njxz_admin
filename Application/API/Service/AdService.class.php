<?php
/**
 * Created by PhpStorm.
 */

namespace API\Service;


use API\Model\AdModel;

class AdService extends APIServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new AdModel();
    }

    /**
     * @brief 获取首页广告Banner
     *
     * @return array|string
     */
    public function getIndexAd()
    {
        $map = [
            'query' => [
                'ad_sort_id' => 1,
            ],
            'order' => [
                'sort_order DESC',
            ],
        ];
        $func = function ($info) {
            $data = [
                'id' => $info['id'],
                'title' => $info['title'],
                'url' => $info['url'],
                'content' => $info['content'],
            ];
            if ($info['cover']) {
                $data['cover'] = IMG_URL . $info['cover'];
            }
            return $data;
        };

        $result = $this->getData($map, $func);

        return message(MESSAGE_OK, true, $result);
    }
}
