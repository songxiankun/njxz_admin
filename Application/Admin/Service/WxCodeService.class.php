<?php

/**
 * 微信小程序二维码
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
class WxCodeService extends ServiceModel {
    protected $appId,$appSecret;
    function __construct() {
        parent::__construct();
        $this->appId = "wxb5dfb8646b328e8a";
        $this->appSecret = "b0f546e8fcf440e91b5a99d1535e4717";
    }
    
    /**
     * 获取access_token
     */
    function getWxAccessToken(){
        if(S('access_token') && S('expires_in') > time() ){
            $accessToken = S('access_token');
        } else {
            //1.请求url地址
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=". $this->appId ."&secret=" . $this->appSecret;
            $res = $this->http_url($url);
            $accessToken = $res['access_token'];
            S('access_token',$res['access_token']);
            S('expires_in',time() + $res['expires_in']);
        }
        return $accessToken;
    }
    
    /**
     * 发起Http请求
     * @param unknown $url
     * @param string $type
     * @param string $res
     * @param string $arr
     * @return mixed
     */
    function http_url($url, $type='get', $res='json', $arr=''){
        //1.初始化curl
        $ch = curl_init();
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        if( $type == 'post' ){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        //3.采集
        $output = curl_exec($ch);
        //4.关闭
        curl_close($ch);
    
        if( curl_errno($ch) ){
            var_dump(curl_error($ch));
        }
    
        if( $res == 'json'){
            return json_decode( $output, true );
        } else {
            return $output;
        }
    }
    
    /**
     * 获取微信二维码
     */
    function getWxcode($page,$scene,$path) {
        $accessToken = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$accessToken;
        $post_data = [
            'page'  =>$page,
            'scene' =>$scene,
        ];
        $post_data = json_encode($post_data);
        $data = $this->send_post($url,$post_data);
        $result = $this->data_uri($data,'image/png');
        $filepath = $this->base64_image_content($result, $path);
        return $filepath;
        
//         return '<image src='.$result.'></image>';
    }
    
    /**
     * 消息推送http
     */
    function send_post($url, $post_data) {
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/json',
                //header 需要设置为 JSON
                'content' => $post_data,
                'timeout' => 60
                //超时时间
            )
        );
        $context = stream_context_create( $options );
        $result = file_get_contents( $url, false, $context );
        return $result;
    }
    
    /**
     * 二进制转图片(image/png)
     */
    function data_uri($contents, $mime) {
        $base64 = base64_encode($contents);
        return ('data:' . $mime . ';base64,' . $base64);
    }
    
    /**
     * base64转二进制图片
     *
     * @param unknown $base64_image_content
     * @param unknown $path
     * @return string|boolean
     */
    function base64_image_content($base64_image_content,$path){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $filename = \Zeus::createImagePath($path,$type);
            if (file_put_contents(IMG_PATH . $filename, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                return $filename;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
}