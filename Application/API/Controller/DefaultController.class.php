<?php

/**
 * 默认-控制器
 */

namespace API\Controller;

use API\Model\UserModel;
use API\Model\SmsLogModel;
use API\Service\DefaultService;

class DefaultController extends APIBaseController
{
    function __construct()
    {
        parent::__construct();
        $this->service = new DefaultService();
    }

    /**
     * @brief 用户注册
     */
    public function register()
    {
        $result = $this->service->useRegister($this->req);
        $this->jsonReturn($result);
    }

    /**
     * 发送短信验证码
     */
    public function sendSMS()
    {

        //手机号码
        $mobile = trim($this->req['mobile']);
        if (!\Zeus::isValidMobile($mobile)) {
            $this->jsonReturn(MESSAGE_MOBILE_INVALID);
        }

        //发送类型
        $type = (int)$this->req['type'] ? (int)$this->req['type'] : 1;
        $content = "验证码：%d, (15分钟内有效，请勿泄露)";

        //注册验证码
        if ($type == 1) {
            $userMod = new UserModel();
            $userInfo = $userMod->getRowByAttr([
                'mobile' => $mobile,
            ]);
            if ($userInfo) {
                $this->jsonReturn(MESSAGE_MOBILE_REGISTERED);
            }
        }

        //修改密码
        if ($type == 2) {
            $this->needLogin();
            $mobile = $this->userInfo['mobile'];
        }

        //找回密码
        if ($type == 3) {
            $userMod = new UserModel();
            $userInfo = $userMod->getRowByAttr([
                'mobile' => $mobile,
            ]);
            if (!$userInfo) {
                $this->jsonReturn(MESSAGE_MOBILE_UNREGISTERED);
            }
        }

        $sms_code = null;
        $rs = \Zeus::createSMSCode($mobile, $sms_code);
        if ($rs) {
            $content = sprintf($content, $sms_code);
            $sign = "安格新能源";

            //创建短信日志
            $smsLogMod = new SmsLogModel();
            $rowId = $smsLogMod->edit([
                'mobile'  => $mobile,
                'type'    => $type,
                'content' => $content,
                'sign'    => $sign,
                'status'  => 3,
                'sender'  => 1,
            ]);
            if (!$rowId) {
                return message('短信发送记录创建失败', false);
            }

            //直接调用运营商发送短信
            $msg = "";
            $status = \SMS::sendSms($mobile, $content, 1, $sign, $msg);

            //更新短信日志状态
            $smsLogMod->edit([
                'id'     => $rowId,
                'status' => $status,
                'msg'    => $msg,
            ]);
            $this->jsonReturn(MESSAGE_OK, true, array('sms_code' => $sms_code));
        } else {
            $this->jsonReturn('短信发送过于频繁，请于1分钟后重试', false);
        }
    }

}