<?php

/**
 * 小程序微信支付
 * 
 * @author zongjl
 * @date 2018-11-19
 */
class WeixinPay {
    
    protected $appid;
    protected $mch_id;
    protected $key;
    protected $openid;
    protected $out_trade_no;
    protected $body;
    protected $total_fee;
    protected $notify_url;
    protected $order_id;
    protected $type;
    function __construct($openid,$order_id,$out_trade_no,$body,$total_fee,$type) {
        
        $this->appid = C('WX_APPID');
        $this->openid = $openid;
        $this->mch_id = C('WX_MCHID');
        $this->key = C('WX_KEY');
        $this->out_trade_no = $out_trade_no;
        $this->body = $body;
        $this->total_fee = $total_fee;
        $this->notify_url = C('WX_NOTIFY_URL');
        $this->order_id = $order_id;
        $this->type = $type;//类型：1砍价支付  2约饭支付 3抢购
    }
    public function pay() {
        //统一下单接口
        $return = $this->weixinapp();
        return $return;
    }
    
    //统一下单接口
    private function unifiedorder() {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $parameters = array(
            'appid' => $this->appid, //小程序ID
            'mch_id' => $this->mch_id, //商户号
            'nonce_str' => $this->createNoncestr(), //随机字符串
            'body' => $this->body,
            'attach'=>"{$this->type},{$this->order_id}",
            'out_trade_no'=> $this->out_trade_no,
            'total_fee' => $this->total_fee,//总金额 单位 分
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], //终端IP
            'notify_url' => $this->notify_url, //通知地址  确保外网能正常访问
            'openid' => $this->openid, //用户id
            'trade_type' => 'JSAPI'//交易类型
        );
        //统一下单签名
        $sign = $this->getSign($parameters);
        $parameters['sign'] = $sign; 
//         $xmlData = $this->arrayToXml($parameters);
//         $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60));
//         return $return;

        $xml=$this->toXml($parameters);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';//接收xml数据的文件
        $header[] = "Content-type: text/xml";//定义content-type为xml,注意是数组
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 兼容本地没有指定curl.cainfo路径的错误
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            // 显示报错信息；终止继续执行
            die(curl_error($ch));
        }
        curl_close($ch);
        $result=$this->toArray($response);
        // 显示错误信息
        if ($result['return_code']=='FAIL') {
            die($result['return_msg']);
        }
        $result['sign'] = $sign;
        $result['nonce_str']='test';
        return $result;
    }
    
    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function toXml($data){
        if(!is_array($data) || count($data) <= 0){
            throw new WxPayException("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($data as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    
    /**
     * 将xml转为array
     * @param  string $xml xml字符串
     * @return array       转换得到的数组
     */
    public function toArray($xml){
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }
    
    /**
     * 验证
     * @return array 返回数组格式的notify数据
     */
    public function notify(){
        // 获取xml
        $xml = file_get_contents('php://input', 'r');
        // 转成php数组
        $data=$this->toArray($xml);
        // 保存原sign
        $data_sign = $data['sign'];
        // sign不参与签名
        unset($data['sign']);
        $sign = $this->getSign($data);
        // 判断签名是否正确  判断支付状态
        if ($sign===$data_sign && $data['return_code']=='SUCCESS' && $data['result_code']=='SUCCESS') {
            $result=$data;
        }else{
            $result=false;
        }
        return $result;
    }
    
    public function notifyToWx($result){
        // 返回状态给微信服务器
        if ($result) {
            $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        }else{
            $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
        }
        echo $str;exit;
    }
    
    
    
    
    
    private static function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
    
    
    
    //数组转换成xml
    private function arrayToXml($arr) {
        $xml = "";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . $this->arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "";
        return $xml;
    }
    
    
    //xml转换成数组
    private function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }
    
    
    //微信小程序接口
    private function weixinapp() {
        //统一下单接口
        $unifiedorder = $this->unifiedorder();
        $parameters = array(
            'appId' => $this->appid, //小程序ID
            'timeStamp' => '' . time() . '', //时间戳
            'nonceStr' => $this->createNoncestr(), //随机串
            'package' => 'prepay_id=' . $unifiedorder['prepay_id'], //数据包
            'signType' => 'MD5'//签名方式
        );
        //签名
        $parameters['paySign'] = $this->getSign($parameters);
        return $parameters;
    }
    
    
    //作用：产生随机字符串，不长于32位
    private function createNoncestr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) { 
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1); 
        } return $str; 
    } 
    
    //作用：生成签名 
    private function getSign($Obj) { 
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }
    
    
    ///作用：格式化参数，签名过程需要使用
    private function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = "";
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
    
}