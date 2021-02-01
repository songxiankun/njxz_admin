<?php


namespace Home\Service;

use Home\Model\AdminModel;
use Home\Model\OrderModel;
use Home\Model\RepairApplicationModel;
use Home\Model\UserModel;

class AdminService extends BaseService
{
    /**
     * @return array|void
     */
    public function initWeb()
    {
        // 首先获取当前用户角色类型
        $data = $this->dataToken(I("token"));

        if (!$data['success']) {
            return message($data['msg'], $data['success'], $data['data']);
        }

        $uid = $data['data']['id'];
        $identify = $data['data']['role'];

        $countDatas = array();          // 数据统计
        $quickHref = array();           // 快速跳转
        $title = array();               // 标题
        $series = array();              // echarts
        $date = array(                  // 日期
            date("m-d", strtotime(("-6 day"))),
            date("m-d", strtotime(("-5 day"))),
            date("m-d", strtotime(("-4 day"))),
            date("m-d", strtotime(("-3 day"))),
            date("m-d", strtotime(("-2 day"))),
            date("m-d", strtotime(("-1 day"))),
            date("m-d", time())
        );
        $mod = null;                    // 数据库操作对象
        $map = array();                 // 查询条件
        // 当天0-24小时之间
        $start = strtotime(date('Y-m-d'));                   // 0
        $end = strtotime(date("Y-m-d")) + 86400;             // 24

        if ($identify == 1) {           // 教师
            // TODO
        }
        elseif (in_array($identify, [2, 3])) {         // 机房管理员
            $mod = new AdminModel();
            // 7天内date 数量
            $map = array([
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 6, $end - 24 * 3600 * 6))),  // -6
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 5, $end - 24 * 3600 * 5))),  // -5
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 4, $end - 24 * 3600 * 4))),  // -4
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 3, $end - 24 * 3600 * 3))),   // -3
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 2, $end - 24 * 3600 * 2))),  // -2
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600, $end - 24 * 3600))),   // -1
                array(
                    'admin_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start, $end)))   // 0
            ]);
        }
        elseif ($identify == 4) {     // 维修人员
            $mod = new UserModel();
            $map = array([
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 6, $end - 24 * 3600 * 6))),  // -6
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 5, $end - 24 * 3600 * 5))),  // -5
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 4, $end - 24 * 3600 * 4))),  // -4
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 3, $end - 24 * 3600 * 3))),   // -3
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600 * 2, $end - 24 * 3600 * 2))),  // -2
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start - 24 * 3600, $end - 24 * 3600))),   // -1
                array(
                    'user_id' => $uid,
                    'mark' => 1,
                    'add_time' => array('between', array($start, $end)))   // 0
            ]);
        }

        // 判断当前用户是否真实
        $count = $mod->where(['id' => $uid])->count();
        if (!$count) { // 权限验证
            return message('非法请求', false, []);
        }

        // 维修申请model
        $repairMod = new RepairApplicationModel();
        // 订单model
        $orderMod = new OrderModel();

        // 数据统计OK --
        if ($identify == 2) {  //  数据统计
            $countDatas[0]['title'] = "申请统计";
            $countDatas[0]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1])->count();
            $countDatas[1]['title'] = "待审批统计";
            $countDatas[1]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 1])->count();
            $countDatas[2]['title'] = "审批通过统计";
            $countDatas[2]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 3])->count();
            $countDatas[3]['title'] = "审批未通过统计";
            $countDatas[3]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 2])->count();
            $countDatas[4]['title'] = "维修统计";
            $countDatas[4]['nums'] = $orderMod->where(['admin_id' => $uid, 'mark' => 1])->count();
            $countDatas[5]['title'] = "待维修统计";
            $countDatas[5]['nums'] = $orderMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 1])->count();
            $countDatas[6]['title'] = "维修中统计";
            $countDatas[6]['nums'] = $orderMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 2])->count();
            $countDatas[7]['title'] = "已维修统计";
            $countDatas[7]['nums'] = $orderMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 3])->count();

            // 快速通道
            $quickHref[0]['href'] = "/page/table/application/create_application.html";
            $quickHref[0]['icon'] = "fa fa-file-text";
            $quickHref[0]['title'] = "创建申请";

            $quickHref[1]['href'] = "/page/table/application/apply.html?t_id=1";
            $quickHref[1]['icon'] = "fa fa-file-text";
            $quickHref[1]['title'] = "待审批";

            $quickHref[2]['href'] = "/page/table/application/apply.html?t_id=2";
            $quickHref[2]['icon'] = "fa fa-gears";
            $quickHref[2]['title'] = "未通过";

            $quickHref[3]['href'] = "/page/table/application/apply.html?t_id=3";
            $quickHref[3]['icon'] = "fa fa-file-text";
            $quickHref[3]['title'] = "已通过";

            $quickHref[4]['href'] = "/page/fix_interface.html?status=1";
            $quickHref[4]['icon'] = "fa fa-calendar";
            $quickHref[4]['title'] = "待维修";
            $quickHref[5]['href'] = "/page/fix_interface.html?status=2";
            $quickHref[5]['icon'] = "fa fa-calendar";
            $quickHref[5]['title'] = "维修中";
            $quickHref[6]['href'] = "/page/fix_interface.html?status=3";
            $quickHref[6]['icon'] = "fa fa-snowflake-o";
            $quickHref[6]['title'] = "维修结束";
        }
        else if ($identify == 3) {
            // 数据统计
            $countDatas[0]['title'] = "审核统计";
            $countDatas[0]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1])->count();
            $countDatas[1]['title'] = "待审批统计";
            $countDatas[1]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 1])->count();
            $countDatas[2]['title'] = "审批通过统计";
            $countDatas[2]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 3])->count();
            $countDatas[3]['title'] = "审批未通过统计";
            $countDatas[3]['nums'] = $repairMod->where(['admin_id' => $uid, 'mark' => 1, 'status' => 2])->count();

            // 快速通达
            $quickHref[0]['href'] = "/page/table/application/list.html?status=1";
            $quickHref[0]['icon'] = "fa fa-file-text";
            $quickHref[0]['title'] = "待审核";

            $quickHref[1]['href'] = "/page/table/application/list.html?status=2";
            $quickHref[1]['icon'] = "fa fa-file-text";
            $quickHref[1]['title'] = "未审核";

            $quickHref[2]['href'] = "/page/table/application/list.html?status=3";
            $quickHref[2]['icon'] = "fa fa-gears";
            $quickHref[2]['title'] = "已通过";
        }
        else if ($identify == 4) { // 维修人员
            $countDatas[0]['title'] = "待接单";
            $countDatas[0]['nums'] = $orderMod->where(['mark' => 1, 'status' => 0])->count();

            $countDatas[1]['title'] = "已接单";
            $countDatas[1]['nums'] = $orderMod->where([
                'mark' => 1,
                'status' => array('neq', 0),
                'user_id' => $uid
            ])->count();

            $countDatas[2]['title'] = "维修统计";
            $countDatas[2]['nums'] = $orderMod->where(['mark' => 1, 'user_id' => $uid])->count();

            $countDatas[3]['title'] = "待维修统计";
            $countDatas[3]['nums'] = $orderMod->where(['user_id' => $uid, 'mark' => 1, 'status' => 1])->count();

            $countDatas[4]['title'] = "维修中统计";
            $countDatas[4]['nums'] = $orderMod->where(['user_id' => $uid, 'mark' => 1, 'status' => 2])->count();

            $countDatas[5]['title'] = "维修结束统计";
            $countDatas[5]['nums'] = $orderMod->where(['user_id' => $uid, 'mark' => 1, 'status' => 3])->count();

            $countDatas[5]['title'] = "待签字";
            $countDatas[5]['nums'] = $orderMod->where(['user_id' => $uid, 'mark' => 1, 'status' => 3,
                'sign_id' => array('eq', null)])->count();

            $countDatas[6]['title'] = "已签字";
            $countDatas[6]['nums'] = $orderMod->where(['user_id' => $uid, 'mark' => 1, 'status' => 3,
                'sign_id' => array('neq', null)])->count();

            // 快速通达  待接单 已接单 待维修 维修中 维修结束 待签字 已签字
            $quickHref[0]['href'] = "/page/table/worker/order/list.html?status=0";
            $quickHref[0]['icon'] = "fa fa-file-text";
            $quickHref[0]['title'] = "待接单";

            $quickHref[1]['href'] = "/page/table/worker/order/list.html?status=1";
            $quickHref[1]['icon'] = "fa fa-file-text";
            $quickHref[1]['title'] = "已接单";

            $quickHref[2]['href'] = "/page/table/worker/order/list.html?status=2";
            $quickHref[2]['icon'] = "fa fa-gears";
            $quickHref[2]['title'] = "待维修";

            $quickHref[3]['href'] = "/page/table/worker/order/list.html?status=3";
            $quickHref[3]['icon'] = "fa fa-gears";
            $quickHref[3]['title'] = "维修中";

            $quickHref[4]['href'] = "/page/table/worker/order/list.html?status=4";
            $quickHref[4]['icon'] = "fa fa-calendar";
            $quickHref[4]['title'] = "维修结束";

            $quickHref[5]['href'] = "/page/table/worker/order/list.html?status=5";
            $quickHref[5]['icon'] = "fa fa-file-text";
            $quickHref[5]['title'] = "待签字";

            $quickHref[6]['href'] = "/page/table/worker/order/list.html?status=6";
            $quickHref[6]['icon'] = "fa fa-calendar";
            $quickHref[6]['title'] = "已签字";
        } else if ($identify == 1) {// 教师
            // TODO 教师下次做
        }
        // echarts 获取标题
        foreach ($countDatas as $k => $v) {
            $title[$k] = $v['title'];
        }


        // echarts series
        for ($i = 0; $i <= 7; $i++) {
            $series[$i]['type'] = 'line';
            $series[$i]['name'] = $title[$i];

            if ($identify == 2) {   // 普通管理员
                for ($j = 0; $j < 7; $j++) {
                    switch ($i) {
                        case 0:
                            $series[$i]['data'][$j] = $repairMod->where($map[$j])->count();
                            break;
                        case 1:
                        {
                            $map[$j]['status'] = 1;
                            $series[$i]['data'][$j] = $repairMod->where($map[$j])->count();
                            break;
                        }
                        case 2:
                        {
                            $map[$j]['status'] = 3;
                            $series[$i]['data'][$j] = $repairMod->where($map)->count();
                            break;
                        }
                        case 3:
                        {
                            $map[$j]['status'] = 2;
                            $series[$i]['data'][$j] = $repairMod->where($map)->count();
                            break;
                        }
                        case 4:
                        {
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 5:
                        {
                            $map[$j]['status'] = 1;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 6:
                        {
                            $map[$j]['status'] = 2;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 7:
                        {
                            $map[$j]['status'] = 3;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                    }
                }
            } else if ($identify == 1)  // 教师
            {
            } else if ($identify == 3) {
                for ($j = 0; $j < 4; $j++) {
                    switch ($i) {
                        case 0:
                            $series[$i]['data'][$j] = $repairMod->where($map[$j])->count();
                            break;
                        case 1:
                        {
                            $map[$j]['status'] = 1;
                            $series[$i]['data'][$j] = $repairMod->where($map[$j])->count();
                            break;
                        }
                        case 2:
                        {
                            $map[$j]['status'] = 3;
                            $series[$i]['data'][$j] = $repairMod->where($map)->count();
                            break;
                        }
                        case 3:
                        {
                            $map[$j]['status'] = 2;
                            $series[$i]['data'][$j] = $repairMod->where($map)->count();
                            break;
                        }
                    }
                }
            } else if ($identify == 4) // 维修工人
            {
                for ($j = 0; $j < 7; $j++) {  // 待接单 已接单 待维修 维修中 维修结束 待签字 已签字
                    switch ($i) {
                        case 0:
                        {
                            $map[$j]['status'] = 0;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 1:
                        {
                            $map[$j]['status'] = array('neq', 0);
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 2:
                        {
                            $map[$j]['status'] = 1;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 3:
                        {
                            $map[$j]['status'] = 2;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 4:
                        {
                            $map[$j]['status'] = 3;
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 5:   // 待签字
                        {
                            $map[$j]['sign_id'] = array('eq', null);
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                        case 6:   // 已签字
                        {
                            $map[$j]['sign_id'] = array('neq', null);
                            $series[$i]['data'][$j] = $orderMod->where($map)->count();
                            break;
                        }
                    }
                }
            }
        }
        $allDatas = array(
            'counts' => $countDatas,
            'quickHref' => $quickHref,
            'title' => $title,
            'date' => $date,
            'series' => $series
        );
        return message("获取成功", true, $allDatas);
    }


    /**
     * 获取用户消息
     * @param $uid
     * @return array
     */
    public function getInfo($uid)
    {
        $adminMod = new AdminModel();
        $info = $adminMod->field('id, avatar, num, mobile, email, note')
            ->where([
                'mark' => 1,
                'id' => $uid
            ])->find();

        if ($info) {
            return message("获取成功", true, $info);
        }

        return message("获取失败", false, []);
    }

    /**
     * 更新用户信息
     * @param $id
     * @return array
     * @author songxk
     */
    public function edit($id)
    {
        $data = I("post.");  // 获取所有信息
        $adminMod = new AdminModel();
        $data['id'] = $id;

        if ($id)
        {
            $counts = $adminMod->where(['mobile' => $data['mobile'], 'mark' => 1])->count();
            if ($counts == 1) {
                $info = $adminMod->field('id')->where(['mobile' => $data['mobile'], 'mark' => 1])->find();
                if ($info['id'] != $id)
                    return message("手机号码已注册", false, []);
            } else if ($counts > 1)
                return message("手机号码已注册", false, []);
        }

        if (isset($data['token']) && $data['token'])
            unset($data['token']);

        unset($data['file']);

        $res = $adminMod->save($data);

        if (!$res) {
            return message("保存失败", false, []);
        }
        return message("保存成功", true, []);
    }

    /**
     * 用户密码更新
     * @param $id
     * @return array
     */
    public function update($id)
    {
        $data = I("post.");
        $old_password = $data['old_password'];
        $new_password = $data['new_password'];
        $again_password = $data['again_password'];

        if ($old_password == $new_password) {
            return message("新密码和旧密码一致", false, []);
        }

        if ($new_password != $again_password) {
            return message("密码与确认密码不一致", false, []);
        }

        $adminMod = new AdminModel();
        $info = $adminMod->field('password')->where(['id' => $id, 'mark' => 1])->find();

        if (!$info) {
            return message("非法操作", false, []);
        }

        $basePass = $info['password'];

        if ($basePass == md5(md5($old_password))) {
            $info['password'] = md5(md5($new_password));
            $res = $adminMod->where(['id'=>$id])->save($info);
            if ($res) {
                return message("更新成功", true, []);
            }
            else
            {
                return message("更新失败", false, []);
            }
        } else {
            return message("旧密码错误", false, []);
        }
    }
}