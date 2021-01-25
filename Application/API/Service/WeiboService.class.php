<?php
/**
 * Created by PhpStorm.
 */

namespace API\Service;


use API\Library\Weibo\lib\SaeTClientV2;
use API\Library\Weibo\lib\SaeTOauthV2;

class WeiboService extends APIServiceModel
{
    private $wb_service;

    public function __construct()
    {
        parent::__construct();
        $this->wb_service = new SaeTOauthV2(C('WB_AKEY'), C('WB_SKEY'));
    }

    public function getUserInfoByWeiBo($code = '')
    {
        if (empty($code)) {
            return message(MESSAGE_PARAMETER_MISSING, false, [], 10001);
        }
        $params = [
            'code'         => $code,
            'redirect_uri' => C('WB_CALLBACK_URL'),
        ];
        /**
         * "access_token": "ACCESS_TOKEN",
         * 通过微博接口获取token
         * 返回数据示例
         * {
         * "expires_in": 1234,
         * "remind_in":"798114",
         * "uid":"12341234"
         * }
         */
        $token = $this->wb_service->getAccessToken('code', $params);
        if (isset($token['error'])) {
            return message($token['error'], false, [], $token['error_code']);
        }
        $open_id = $token['uid'];
        $access_token = $token['access_token'];
        //检测用户是否是新用户，根据openid查询第三方用户表，若存在用户，视为老用户，否则视为新用户，并将openid写入第三方用户表
        $third_user_info = D('ThirdUser')->getUserByOpenId($open_id, 'user_id,openid,nickname,avatar');
        if ($third_user_info) {
            $user_info = [
                'id'        => intval($third_user_info['user_id']),
                'nick_name' => $third_user_info['nickname'],
                'avatar'    => $third_user_info['avatar'],
                'openid'    => $third_user_info['openid'],
                'is_new'    => 1,//是否新用户 0是 1否
            ];
        } else {
            $wb_client = new SaeTClientV2(C('WB_AKEY'), C('WB_SKEY'), $access_token);
            //获取微博的用户id
            $wb_uid_get = $wb_client->get_uid();
            if ($wb_uid_get['error_code']) {
                return message($wb_uid_get['error'], false, [], $wb_uid_get['error_code']);
            }
            $wb_uid = $wb_uid_get['uid'];
            //拉取微博的用户信息
            $wb_user_info = $wb_client->show_user_by_id($wb_uid);
            if ($wb_user_info['error_code']) {
                return message($wb_user_info['error'], false, [], $wb_user_info['error_code']);
            }
            //用户信息写入第三方用户表
            $data = [
                'user_id'     => '',
                'openid'      => $wb_user_info['id'],//微博在openid字段存储用户ID，该ID和微信的openid是同样的性质
                'type'        => 2,
                'nickname'    => $wb_user_info['screen_name'],
                'avatar'      => $wb_user_info['profile_image_url'],
                'create_time' => time(),
            ];
            switch ($wb_user_info['gender']) {
                case 'm':
                    //男
                    $data['sex'] = 1;
                    break;
                case 'f':
                    //女
                    $data['sex'] = 2;
                    break;
                case 'n':
                    //未知或保密
                    $data['sex'] = 0;
                    break;
            }
            $result = D('ThirdUser')->addThirdUser($data);
            if ($result === false) {
                return message('授权登录失败', false, [], 10002);
            }
            $user_info = [
                'id'        => 0,
                'nick_name' => $data['nickname'],
                'avatar'    => $data['avatar'],
                'openid'    => $open_id,
                'is_new'    => 0,
            ];
        }

        return message(MESSAGE_OK, true, $user_info);
    }
}
