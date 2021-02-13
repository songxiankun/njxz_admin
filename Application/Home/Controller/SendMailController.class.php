<?php


namespace Home\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class SendMailController
 * @package Home\Controller
 */
class SendMailController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
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
        // 开启异常捕捉
        $mail = new PHPMailer(true);
        try {
            //服务器配置
            $mail->CharSet      = C("send_email_config.charset");           //设定邮件编码
            $mail->isSMTP();                                                // 使用SMTP
            $mail->Host         = C("send_email_config.host");              // SMTP服务器
            $mail->SMTPAuth     = C("send_email_config.smtpAuth");          // 允许 SMTP 认证
            $mail->SMTPAutoTLS  = C("send_email_config.smtpAutoTLS");
            $mail->Username     = C("send_email_config.username");          // SMTP 用户名  即邮箱的用户名
            $mail->Password     = C("send_email_config.password");          // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->Port         = C("send_email_config.port");              // 服务器端口 25 或者465 具体要看邮箱服务器支持


            /* 发送邮件题  可变 */
            $mail->setFrom(C("send_email_config.username"), C("send_email_config.nickname"));          // 发件人

            // 判断是否批量放邮件
            if (sizeof($data['toAddress']) > 1  && $data['toAddress'])
            {
                for ($i = 0; $i < sizeof($data['toAddress']); $i++) {
                    $mail->addAddress($data['toAddress'][$i], $data['toName'][$i]);    // 收件人
                }
            }
            else
                $mail->addAddress($data['toAddress'], $data['toName']);    // 收件人

            // 内容区域
            // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body    = $data['htmlData'];
            $mail->AltBody = $data['data'];

            $mail->send();
            $this->ajaxReturn(message('邮件发送成功', true, [], 200));
        } catch (Exception $e) {
            $this->ajaxReturn(message('邮件发送失败：'. $mail->ErrorInfo, false, [], 200));
        }
    }
}