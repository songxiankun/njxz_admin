<?php
/**
 * Created by PhpStorm.
 */

namespace Admin\Service;


use Admin\Model\ServiceModel;
use Admin\Model\SmsModel;

class SmsService extends ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new SmsModel();
    }

    public function getList()
    {
        $data = I('request.');
        $params = [
            'page'  => $data['page'],
            'limit' => $data['limit'],
        ];
        $mobile = trim($data['mobile']);
        if ($mobile) {
            $params['mobile'] = $mobile;
        }
        list($count, $list) = $this->mod->getSmsLog($params, 'id,mobile,content,add_time,status');
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
            }
        }
        $message = array(
            "msg"   => '操作成功',
            "code"  => 0,
            "data"  => $list,
            "count" => $count,
        );

        return $message;
    }

    public function edit()
    {
        $mobile = I('post.mobile');
        $sms_content = I('post.sms_content');
        if (!$mobile) {
            return message('手机号不可为空', false);
        }
        if (!$sms_content) {
            return message('短信内容不可为空', false);
        }
        //短信签名
        $sms_sign = '我为“最佳天坛实习生”打call';
        //创建短信日志
        $data = [
            'mobile'   => $mobile,
            'type'     => 7,
            'content'  => $sms_content,
            'sign'     => $sms_sign,
            'add_time' => time(),
            'status'   => 3,
        ];
        $rowId = $this->mod->edit($data);
        if (!$rowId) {
            return message('短信发送失败', false);
        }
        //直接调用运营商发送短信
        $msg = "";
        $status = \SMS::sendSms($mobile, $sms_content, 1, $sms_sign, $msg);

        //更新短信日志状态
        $this->mod->edit([
            'id'     => $rowId,
            'status' => $status,
            'msg'    => $msg,
        ]);
        return message('短信发送成功', true);
    }
}
