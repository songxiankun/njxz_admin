<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2019/9/4 0004
 * Time: 14:58:30
 */

namespace API\Service;


use API\Library\Common\Tools;
use API\Model\UserModel;
use Think\Cache\Driver\Redis;
use Think\Log;

class WeixinService extends APIServiceModel
{
    // private $LOG_INFO = "[WeiXinService]\t";
    const KEY = 'ACCESS_TOKEN';
    const TICKET_KEY = 'JS_API_TICKET';
    private $redis;

    public function __construct()
    {
        parent::__construct();
        $this->redis = new Redis();
    }

    /**
     * @brief 构造获取openId和access_toke的url地址
     *
     * @param string $code 从微信侧获取的code
     * @return string
     */
    public function createOauthUrlForOpenId($code = '')
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $params = [
            'appid'      => C('WX_APPID'),
            'secret'     => C('WX_APPSECRET'),
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
        $url .= Tools::urlFormat($params);

        return $url;
    }

    /**
     * @brief 构造拉取微信用户信息的url地址
     *
     * @param string $openId
     * @param string $accessToken
     * @return string
     */
    private function createOauthUrlForWxUserInfo($openId = '', $accessToken = '')
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?';
        $params = [
            'access_token' => $accessToken,
            'openid'       => $openId,
            'lang'         => 'zh_CN',
        ];
        $url .= Tools::urlFormat($params);

        return $url;
    }

    /**
     * @brief 根据微信openid获取用户信息
     *
     * @param string $openId
     * @return mixed
     */
    public function getInfoByOpenId($openId = '')
    {
        $info = $this->mod->getInfoByOpenId($openId, 'id,user_nicename as nick_name,avatar,openid');

        return $info;
    }

    /**
     * @brief 拉取微信服务器的用户信息
     *
     * @param string $openId
     * @param string $accessToken
     * @return mixed
     * @throws \Think\Exception
     */
    public function getWxUserInfo($openId = '', $accessToken = '')
    {
        $url = $this->createOauthUrlForWxUserInfo($openId, $accessToken);
        $params = [
            'url' => $url,
        ];
        $wx_user_info = json_decode(Tools::curl($params), true);

        return $wx_user_info;
    }

    /**
     * @brief 获取js-sdk权限验证配置
     *
     * @param string $url 页面url不包含#及其后面部分
     * @return array|string
     */
    public function getJsSdkSignature($url)
    {
        if (!$url) {
            return message(MESSAGE_PARAMETER_MISSING, false, [], 10001);
        }
        $url = urldecode($url);
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
            return message('请输入有效的URL地址', false, [], 10002);
        }
        //获取access_token
        $access_token = $this->getAccessToken();
        if (isset($access_token['errcode'])) {
            return message($access_token['errmsg'], false, [], $access_token['errcode']);
        }
        //获取js_api_ticket
        $js_api_ticket = $this->getJsApiTicket($access_token);
        if (isset($js_api_ticket['errcode']) && $js_api_ticket['errcode'] != 0) {
            return message($js_api_ticket['errmsg'], false, [], $js_api_ticket['errcode']);
        }
        /**
         * 生成签名
         * 签名生成规则如下：
         * 参与签名的字段包括noncestr（随机字符串）,
         * 有效的jsapi_ticket, timestamp（时间戳）,
         * url（当前网页的URL，不包含#及其后面部分） 。
         * 对所有待签名参数按照字段名的ASCII码从小到大排序（字典序）后，
         * 使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串string1。
         * 这里需要注意的是所有参数名均为小写字符。对string1作sha1加密，字段名和字段值都采用原始值，不进行URL 转义。
         */
        $data = [
            'noncestr'     => \Zeus::getRandCode(16),
            'jsapi_ticket' => $js_api_ticket,
            'timestamp'    => time(),
            'url'          => $url,
        ];
        // $this->LOG_INFO .= '[微信签名的参数:]' . json_encode($data) . "\t";
        $signature = Tools::makeJsSdkSign($data);
        $result = [
            'noncestr'  => $data['noncestr'],
            'timestamp' => $data['timestamp'],
            'signature' => $signature
        ];

        return message(MESSAGE_OK, true, $result);
    }

    /**
     * @brief 获取AccessToken 不对外输出数据
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        /**
         * 先从缓存中获取access_token，如果没有请求微信侧的access_token接口
         */
        $access_token = $this->redis->get(self::KEY);
        $expires_in = $this->redis->ttl(C('CKEY') . '_' . self::KEY);
        if (!$access_token || $expires_in < 60) {
            return $this->getAccessTokenByWeiXin();
        } else {
            return $access_token;
        }
    }

    /**
     * @brief 获取jsapi_ticket 不对外输出数据
     *
     * @param $accessToken
     * @return mixed
     */
    public function getJsApiTicket($accessToken)
    {
        /**
         * 先从缓存中获取jsapi_ticket，如果没有请求微信侧的jsapi_ticket接口
         */
        $js_api_ticket = $this->redis->get(self::TICKET_KEY);
        $expires_in = $this->redis->ttl(C('CKEY') . '_' . self::TICKET_KEY);
        if (!$js_api_ticket || $expires_in < 60) {
            return $this->getJsApiTicketByWeiXin($accessToken);
        } else {
            return $js_api_ticket;
        }
    }

    /**
     * @brief 从微信侧获取access_token
     *
     * @return mixed
     * @throws \Think\Exception
     */
    private function getAccessTokenByWeiXin()
    {
        $url = $this->createAccessTokenUrl();
        $params = [
            'url' => $url
        ];
        $token = json_decode(Tools::curl($params), true);
        if ($token['access_token'] || $token['errcode'] == 0) {
            /**获取access_token成功，将其存入redis
             * access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。
             * 暂时不做主动刷新，当请求接口是先判断是否存在access_token，不存在请求该接口刷新token
             */
            $this->redis->set(self::KEY, $token['access_token'], $token['expires_in']);
            return $token['access_token'];
        } else {
            return $token;
        }
    }

    /**
     * @brief 构造获取access_token的URL地址
     * 此access_token是公众号的全局唯一接口调用凭据与网页授权中的access_token不是一个
     *
     * @return string
     */
    private function createAccessTokenUrl()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?';
        $params = [
            'appid'      => C('WX_APPID'),
            'secret'     => C('WX_APPSECRET'),
            'grant_type' => 'client_credential',
        ];
        $url .= Tools::urlFormat($params);

        return $url;
    }

    /**
     * @brief 请求微信侧获取jsapi_ticket
     *
     * @param string $accessToken
     * @return mixed
     * @throws \Think\Exception
     */
    private function getJsApiTicketByWeiXin($accessToken)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?';
        $params = [
            'access_token' => $accessToken,
            'type'         => 'jsapi',
        ];

        $url .= Tools::urlFormat($params);
        $data = [
            'url' => $url
        ];
        $js_api = json_decode(Tools::curl($data), true);
        if ($js_api['ticket'] || $js_api['errcode'] == 0) {
            /**获取js_api_ticket成功，将其存入redis
             * js_api_ticket的有效期为2个小时,
             * 暂时不做主动刷新，当请求接口是先判断是否存在js_api_ticket，不存在请求该接口刷新js_api_ticket
             */
            $this->redis->set(self::TICKET_KEY, $js_api['ticket'], $js_api['expires_in']);
            return $js_api['ticket'];
        } else {
            return $js_api;
        }
    }

    // public function __destruct()
    // {
    //     $this->LOG_INFO = str_replace("\n\r", '', $this->LOG_INFO);
    //     $this->LOG_INFO = str_replace("\n", '', $this->LOG_INFO);
    //     $this->LOG_INFO = str_replace("\r", '', $this->LOG_INFO);
    //     $destination = C('LOG_PATH') . 'WeiXinService/' . date('Y-m-d') . '.log';
    //     //记录日志
    //     Log::write($this->LOG_INFO, Log::INFO, '', $destination);
    // }
}
