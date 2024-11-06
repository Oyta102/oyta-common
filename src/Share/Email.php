<?php

namespace Oyta\Common\Share;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class Email
{

    /*发送邮件方法
     *@param $host: 服务器地址 $user:登陆账号 $pass:授权码 $ssl:加密方式 $port:端口 $email:发送人 $name:发送人名称  $to：接收者 $title：标题 $content：邮件内容
     *@return bool true:发送成功 false:发送失败
     */
    public static function sendEmail($host, $user, $pass, $ssl, $port, $email, $name, $to, $title, $content){
        //实例化PHPMailer核心类
        $mail = new PHPMailer(true);

        try {
            //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            //使用smtp鉴权方式发送邮件
            $mail->isSMTP();
            //链接域名邮箱的服务器地址
            $mail->Host       = $host;
            //smtp需要鉴权 这个必须是true
            $mail->SMTPAuth   = true;
            //smtp登录的账号
            $mail->Username   = $user;
            //smtp登录的密码 使用生成的授权码
            $mail->Password   = $pass;
            //设置使用ssl加密方式登录鉴权
            $mail->SMTPSecure = $ssl;
            //设置ssl连接smtp服务器的远程服务器端口号
            $mail->Port       = $port;
            //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
            $mail->setFrom($email, $name);
            //设置收件人邮箱地址
            $mail->addAddress($to);               //Name is optional
            //Content
            //邮件正文是否为html编码
            $mail->isHTML(true);
            //添加该邮件的主题
            $mail->Subject = $title;
            //添加邮件正文
            $mail->Body    = $content;
            $mail->send();
            //echo 'Message has been sent';
            return true;
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}
