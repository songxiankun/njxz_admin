<?php
/**
 * Created by PhpStorm.
 */

namespace API\Library\Common;

use Think\Exception;

class Tools
{
    /**
     * @brief 生成签名
     *
     * @param $params
     * @return string
     */
    public static function makeSign($params)
    {
        //签名步骤一：按字典序排序参数
        ksort($params);
        $sign = self::urlFormat($params);
        //签名步骤二：在string后加入KEY
        $sign = $sign . '&key=' . C('WX_PAY.key');
        //签名步骤三：MD5加密或者HMAC-SHA256
        $sign = md5($sign);
        //签名步骤四：所有字符转为大写
        return strtoupper($sign);
    }

    /**
     * @brief JS-SDK使用权限签名算法
     *
     * @param array $params 待签名的参数数组
     * @return string
     */
    public static function makeJsSdkSign($params)
    {
        //步骤1. 对所有待签名参数按照字段名的ASCII码从小到大排序（字典序）后，
        //使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串
        ksort($params);
        $sign = self::urlFormat($params);
        //步骤2. 对string1进行sha1签名，得到signature
        $sign = sha1($sign);

        return $sign;
    }

    /**
     * @brief 将数组转为xml
     *
     * @param $array
     */
    public static function arrayToXml($array)
    {
        if (!is_array($array) || count($array) == 0) {
            throw new Exception('数组数据异常');
        }
        $xml = "<xml>";
        foreach ($array as $k => $v) {
            if (is_numeric($v)) {
                $xml .= "<" . $k . ">" . $v . "</" . $k . ">";
            } else {
                $xml .= "<" . $k . "><![CDATA[" . $v . "]]></" . $k . ">";
            }
        }

        $xml .= "</xml>";

        return $xml;
    }

    /**
     * @brief 将xml转为array
     *
     * @param $xml
     */
    public static function xmlToArray($xml)
    {
        if (!$xml) {
            throw new Exception('xml数据异常！');
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xml_data = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        return json_decode(json_encode($xml_data), true);
    }

    /**
     * @brief 将数组转行url参数格式
     */
    public static function urlFormat($params)
    {
        $buff = '';
        foreach ($params as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * @brief 获取客户端地址
     *
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getClientIp($type = 0, $adv = false)
    {
        /** @var TYPE_NAME $type */
        $type = $type ? 1 : 0;
        static $ip = null;
        if ($ip !== null) {
            return $ip[$type];
        }
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * @brief curl操作封装
     *
     * @param $params
     * @return bool|string
     * @throws Exception
     */
    public static function curl($params)
    {
        $ch = curl_init();
        //设置超时
        $second = 30;
        if (isset($params['second'])) {
            $second = $params['second'];
        }
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //配置代理
        if (isset($params['proxy'])) {
            curl_setopt($ch, CURLOPT_PROXY, $params['proxy']['host']);
            curl_setopt($ch, CURLOPT_PROXYPORTYP, $params['proxy']['port']);
        }
        curl_setopt($ch, CURLOPT_URL, $params['url']);
        if (isset($params['verify']) && $params['verify'] === true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (isset($params['cert'])) {
            //设置证书 cert与key分属两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $params['cert']['cert']);

            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $params['cert']['key']);
        }
        if (isset($params['method']) && strtoupper($params['method']) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params['fields']);
        }
        //执行curl请求
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception($error);
        }
        curl_close($ch);
        return $result;
    }
}
