<?php


namespace Home\Controller;


use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Think\Controller;
use Think\Exception;

/**
 * 基础控制器
 * Class BaseController
 * @package Home\Controller
 */
//解决API文档跨域调试请求的问题
header("Access-Control-Allow-Origin: *");

class BaseController extends Controller
{
    public function __construct($flag = true)
    {
        parent::__construct();
        // token验证
        $res = $this->dataToken(I("token"), $flag);
//        var_dump($res);die();
        // 如果token验证失败直接返回消息
        if (!empty($res) && $res['success'] == false) {
            $this->ajaxReturn($res);
        }
    }

    /**
     * token解析数据
     * @param $token
     * @param $flag
     * @return array I('token', true))['data']['userInfo']
     */
    public function dataToken($token, $flag = true)
    {
        if ($flag) {
            if ($token == "") {
                return message('请登录～', false, [], 404);
            }
            //key要和签发的时候一样
            try {
                $decoded = JWT::decode($token, C("user_token_key"), ['HS256']); //HS256方式，这里要和签发的时候对应
                $data = (array)$decoded;
                return $data['exp'] < time() ? message("token过期", false, [], 404) :
                    message('获取成功', true, ['data' => json_decode($data['sub'], true)], 200);
            } catch (SignatureInvalidException $e) {    //签名不正确
                return message("token不合法！tips:".$e->getMessage()."!请重新登陆", false, [], 404);
            } catch (BeforeValidException $e) {         // 签名在某个时间点之后才能用
                return message("token使用时间不正确！tips:".$e->getMessage()."!请重新登陆", false, [], 404);
            } catch (ExpiredException $e) {             // token过期
                return message("token过期！tips:".$e->getMessage()."!请重新登陆", false, [], 404);
            } catch (Exception $e) {                    //其他错误
                return message("token解析异常！tips:".$e->getMessage()."!请重新登陆", false, [], 404);
            }
        }
        return [];
    }
}