<?php


namespace API\Controller;
include_once "Vendor/aes-wx/errorCode.php";
include_once "Vendor/aes-wx/wxBizDataCrypt.php";

/**
 * 微信小程序授权操作
 * Class WxDecode
 * @package API\Controller
 */
class WXDecodeController extends APIBaseController
{
    /**
     * @brief 发送http请求
     * @param $url
     * @return bool|string
     */
    public function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    /**
     * @brief 解密微信用户敏感数据
     * @url   /WXDecode/WxDecode
     * @return false|string
     */
    public function WxDecode()
    {
        // 接收参数
        $data = I("request.");
        // 引入解密文件 在微信小程序开发文档下载
//        Vendor('aes-wx.WXBizDataCrypt');
//        Vendor('aes-wx.ErrorCode');
        $appid = "wx80ddefec9ac9f328";                     // appid
        $appsecret = "8fc577a10ae50ed8eb3b4b6893fe1276";   // appsecret
        $grant_type = "authorization_code"; //授权（必填）
        $code = $data['code'];    //有效期5分钟 登录会话
        $encryptedData = $data['encryptedData'];
        $iv = $data['iv'];
        $signature = $data['signature'];
        $rawData = $data['rawData'];
        // 拼接url
        $url = "https://api.weixin.qq.com/sns/jscode2session?" . "appid=" . $appid .
            "&secret=" . $appsecret . "&js_code=" . $code . "&grant_type=" . $grant_type;
        $res = json_decode($this->httpGet($url), true);
        $sessionKey = $res['session_key']; //取出json里对应的值
        $signature2 = sha1(htmlspecialchars_decode($rawData) . $sessionKey);
        // 验证签名
        if ($signature2 !== $signature) {
            return json_encode("验签失败");
        }
        // 获取解密后的数据
        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $data = [
                'status' => 0,
                'data' => $data
            ];
            $this->ajaxReturn($data);
            //return json_encode($data);
        } else {
            $this->jsonReturn($errCode, false);
        }
    }


    public function test()
    {

        echo json_encode($data, true);
    }
}