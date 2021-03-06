<?php


namespace Home\Service;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Home\Model\ConfigModel;
use Home\Model\SMSLogModel;
use PHPMailer\PHPMailer\PHPMailer;
use Think\Exception;
use Think\Model;

//解决API文档跨域调试请求的问题
header("Access-Control-Allow-Origin: *");
class BaseService extends Model
{
    public function __construct()
    {
    }

    /**
     * token 生成
     * @param $id       用户id
     * @param $role     用户身份
     * @param $realname 真实姓名
     * @param $orgID    组织id
     * @param $deptID   部门id
     * @return string
     */
    public function getToken($id, $role, $realname, $orgID, $deptID)
    {
        $token = [
            "iat" => time(),           // 签发时间
            "nbf" => time(),           // 在什么时候jwt开始生效
            "exp" => time() + 7200,    // token 过期时间     该程一周
            "sub" => json_encode(array(
                    'id' => $id,
                    'role' => $role,
                    'realname' => $realname,
                    'organization_id' => $orgID,
                    'department_id' => $deptID)
            ) //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
        ];
        return JWT::encode($token, C("user_token_key")); //根据参数生成了 token
    }

    /**
     * token解析数据
     * @param $token
     * @return array
     */
    public function dataToken($token)
    {
        if ($token == "") {
            return ['msg' => ' token为空', 'success' => fasle, 'data' => [], 'code' => 404];
        }
        //key要和签发的时候一样
        try {
            $decoded = JWT::decode($token, C("user_token_key"), ['HS256']); //HS256方式，这里要和签发的时候对应
            $data = (array)$decoded;
            return $data['exp'] < time() ? ['msg' => 'token过期', 'success' => fasle, 'data' => [], 'code' => 404] :
                ['success' => true, 'msg' => '获取成功', 'data' => json_decode($data['sub'], true), 'code' => 200];
        } catch (SignatureInvalidException $e) {    //签名不正确
            return ['msg' => "token不合法！tips:" . $e->getMessage() . "!请重新登陆", 'success' => fasle, 'data' => [], 'code' => 404];
        } catch (BeforeValidException $e) {         // 签名在某个时间点之后才能用
            return ['msg' => "token使用时间不合法！tips:" . $e->getMessage() . "!请重新登陆", 'success' => fasle, 'data' => [], 'code' => 404];
        } catch (ExpiredException $e) {             // token过期
            return ['msg' => "token过期！tips:" . $e->getMessage() . "!请重新登陆", 'success' => fasle, 'data' => [], 'code' => 404];
        } catch (Exception $e) {                    //其他错误
            return ['msg' => "token解析异常！tips:" . $e->getMessage() . "!请重新登陆", 'success' => fasle, 'data' => [], 'code' => 404];
        }
    }

    /**
     * 发送邮件
     * @param array $data => [toAddress (array or string), toName(array or string), subject, htmlData, data]
     * @return array
     */
    public function sendEmail($data = array(
        'toAddress' => '1281541477@qq.com',
        'toName'    => 'kunkun',
        'subject'   => '这个是主题',
        'htmlData'  => '<h1>南京晓庄学院测试信息</h1>',
        'data'      =>  '这个是去除html的信息'
    ))
    {
        header('Access-Control-Allow-Origin: *');
        if ($this->email_isOn()) {   // 查看是否开启发送邮件功能
            // 开启异常捕捉
            $mail = new PHPMailer(true);
            try {
                //服务器配置
                $mail->CharSet = C("send_email_config.charset");           //设定邮件编码
                $mail->isSMTP();                                                // 使用SMTP
                $mail->Host = C("send_email_config.host");              // SMTP服务器
                $mail->SMTPAuth = C("send_email_config.smtpAuth");          // 允许 SMTP 认证
                $mail->SMTPAutoTLS = C("send_email_config.smtpAutoTLS");
                $mail->Username = C("send_email_config.username");          // SMTP 用户名  即邮箱的用户名
                $mail->Password = C("send_email_config.password");          // SMTP 密码  部分邮箱是授权码(例如163邮箱)
                $mail->Port = C("send_email_config.port");              // 服务器端口 25 或者465 具体要看邮箱服务器支持


                /* 发送邮件题  可变 */
                $mail->setFrom(C("send_email_config.username"), C("send_email_config.nickname"));          // 发件人

                $datas = array();
                // 判断是否批量放邮件
                if (sizeof($data['toAddress']) > 1 && $data['toAddress']) {
                    $datas['mail'] = json_encode($data['toAddress'], true);
                    for ($i = 0; $i < sizeof($data['toAddress']); $i++) {
                        $mail->addAddress($data['toAddress'][$i], $data['toName'][$i]);    // 收件人
                    }
                } else {
                    $datas['mail'] = $data['toAddress'];
                    $mail->addAddress($data['toAddress'], $data['toName']);    // 收件人
                }

                // 内容区域
                // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
                $mail->isHTML(true);
                $mail->Subject = $data['subject'];
                $mail->Body = $data['htmlData'];
                $mail->AltBody = $data['data'];

                $mail->send();

                // 写入日志 到sms_log
                $datas['type'] = 2;
                $datas['content'] = $data['htmlData'];
                $datas['sender_id'] = C("send_email_config.username");
                $datas['add_time'] = time();
                // 是否发送成功
                $datas['status'] = 1;
                $datas['msg'] = '邮件发送成功';
                // 日志入库
                $smsLog = new SMSLogModel();
                $smsLog->add($datas);
                return (message('邮件发送成功', true, [], C("code")['ok']));
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                // 写入日志 到sms_log
                $datas['type'] = 2;
                $datas['content'] = $data['htmlData'];
                $datas['sender_id'] = C("send_email_config.username");
                $datas['add_time'] = time();
                // 是否发送成功
                $datas['status'] = 2;
                $datas['msg'] = '邮件发送失败：' . $mail->ErrorInfo;
                // 日志入库
                $smsLog = new SMSLogModel();
                $smsLog->add($datas);
                return (message('邮件发送失败：' . $mail->ErrorInfo, false, [], C("code")['ok']));
            }
        } else {
            return message('邮箱发送功能已关闭', false, [], C("code")['email_close']);
        }
    }

    /**
     * 查询是否开启邮箱发布功能
     * @return integer
     */
    protected function email_isOn()
    {
        // 查询配置文件获取当前是否发送邮件
        $configMod = new ConfigModel();
        return $configMod->where(['mark' => 1, 'status' => 1, 'type' => C("sys_info_config")['type_mail']])->count();
    }

    /**
     * 查询是否开启委托模式
     * @return integer
     */
    protected function delegate_isON()
    {
        // 查询配置文件获取当前是否发送邮件
        $configMod = new ConfigModel();
        return $configMod->where(['mark' => 1, 'status' => 1, 'type' => C("sys_info_config")['type_delegate']])->count();
    }
}