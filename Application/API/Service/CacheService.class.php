<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2019/9/2 0002
 * Time: 14:14:34
 */

namespace API\Service;


use Think\Cache\Driver\Redis;

class CacheService
{
    const PRIZE_KEY = 'prize_list';
    private $redis;

    public function __construct()
    {
        $this->redis = new Redis();
    }


}
