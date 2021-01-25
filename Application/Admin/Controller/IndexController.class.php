<?php

/**
 * 后台主页-控制器
 */

namespace Admin\Controller;

use Admin\Model\ConfigModel;
use Admin\Model\OrderModel;
use Admin\Model\RepairApplyModel;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页入口
     *
     * @author zongjl
     * @date 2018-06-21
     */
    public function index()
    {
        $this->display();
    }


    /**
     * 后台主页入口
     */
    public function main()
    {
        // 维修申请统计
        $repairMod = new RepairApplyModel();
        $stime = strtotime(date("Y-m-d 0:00:00", time()));
        $etime = strtotime(date("Y-m-d 23:59:59", time()));
        $repairNum = $repairMod->getCount("add_time between {$stime} and {$etime} and mark=1");
        $this->assign('todayNums1', (int)$repairNum);
        $repairNum2 = $repairMod->getCount();
        $this->assign('allNums1', (int)$repairNum2);

        // 维修中统计 1、待维修 2、维修中 3、维修结束
        $orderMod = new OrderModel();
        $stime = strtotime(date("Y-m-d 0:00:00", time()));
        $etime = strtotime(date("Y-m-d 23:59:59", time()));
        $orderNum = $orderMod->getCount("add_time between {$stime} and {$etime} and mark=1 and status = 2");
        $this->assign('todayNums2', (int)$orderNum);
        $orderNum2 = $orderMod->getCount("mark=1 and status = 2");
        $this->assign('allNums2', (int)$orderNum2);

        // 维修结束统计 1、待维修 2、维修中 3、维修结束
        $stime = strtotime(date("Y-m-d 0:00:00", time()));
        $etime = strtotime(date("Y-m-d 23:59:59", time()));
        $orderNum3 = $orderMod->getCount("add_time between {$stime} and {$etime} and mark=1 and status = 3");
        $this->assign('todayNums3', (int)$orderNum3);
        $orderNum3 = $orderMod->getCount("mark=1 and status = 3");
        $this->assign('allNums3', (int)$orderNum3);

        // 维修订单
        $stime = strtotime(date("Y-m-d 0:00:00", time()));
        $etime = strtotime(date("Y-m-d 23:59:59", time()));
        $orderNum4 = $orderMod->getCount("add_time between {$stime} and {$etime} and mark=1");
        $this->assign('todayNums4', (int)$orderNum4);
        $orderNum4 = $orderMod->getCount();
        $this->assign('allNums4', (int)$orderNum4);

        // 获取消息
        $configMod = new ConfigModel();
        $info = $configMod->field('id, name, status')->where(['mark' => 1])->select();
        $this->assign('configInfo', $info);
       // $this->SystemInfo();

        $this->display();
    }

//    public function SystemInfo()
//    {
//        // 获取某时刻的cpu和内存使用情况
//        $fp = popen('top -b -n 2 | grep -E "^(Cpu|Mem|Tasks)"',"r");
//        $res = "";
//
//        var_dump($fp);die();
//
//        while (!feof($fp))
//        {
//            $res .= fread($fp, 1024);
//        }
//        pclose($fp);
//
//        $sys_info = explode('\n', $res);
//        var_dump($sys_info);die();
//
//    }
}