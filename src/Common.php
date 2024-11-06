<?php

namespace Oyta\Common;

use Oyta\Common\BlockChain\Bitcoin\BtcAddress;
use Oyta\Common\BlockChain\Ethereum\EthAddress;
use Oyta\Common\BlockChain\Tron\TronAddress;
use Oyta\Common\Share\GoogleAuthenticator;
use Oyta\Common\Share\Bround;
use Oyta\Common\Share\Email;
use Oyta\Common\Share\Pass;
use Oyta\Common\Share\Rate;

class Common
{
    public function __construct()
    {

    }

    /**
     * 设置密码
     * @param string $data //明文密码
     */
    protected function setPass($data){
        return Pass::setPas($data);
    }

    /**
     * 盐生成密码进行比对
     * @param string $salt //盐
     * @param string $data //明文密码
     */
    protected function verPass($salt,$data){
        return Pass::getPas($salt,$data);
    }

    /**
     * 金额格式化-逗号分割
     * @param $data //金额
     * @param $mire //保留小数位数
     */
    protected function setFotMoney($data,$mire){
        return number_format($data,$mire);
    }

    /**
     * 汇率查询-返回各类汇率
     * @param string $data //币种简称 如 cny btc 等
     */
    protected function getRate($data){
        return Rate::exchange($data);
    }

    /**
     * 随机数生成
     * @param int $num //随机数位数
     * @param string $type //随机数类型 默认小写字母
     * type类型 n-数字 s-小写字母 c-大写字母 y-特殊符号 其余随意组合 例: ns或sn sc或cs
     */
    protected function setRound($num,$type){
        return Bround::getRund($num,$type);
    }

    /**
     * 设置谷歌令牌
     * @param $name //令牌标识-名称
     */
    protected function setGcode($name){
        $goole = new GoogleAuthenticator();
        $secret = $goole->createSecret();
        $qrcode = $goole->getQRCodeGoogleUrl($name,$secret);
        return ['secret'=>$secret,'qrcode'=>$qrcode];
    }

    /**
     * 获取谷歌令牌
     * @param $name //令牌标识-名称
     * @param $secret //令牌密钥
     */
    protected function getGcode($name,$secret){
        $goole = new GoogleAuthenticator();
        $qrcode = $goole->getQRCodeGoogleUrl($name,$secret);
        return ['secret'=>$secret,'qrcode'=>$qrcode];
    }

    /**
     * 验证谷歌令牌
     * @param $secret //令牌密钥
     * @param $code //动态验证码
     */
    protected function verGcode($secret,$code){
        $goole = new GoogleAuthenticator();
        $checkResult = $goole->verifyCode($secret,$code,4);
        if(!$checkResult){
            return false;
        }
        return true;
    }

    /**
     * 生成BTC钱包地址
     */
    protected function getBtcAddress(){
        return BtcAddress::createAddress();
    }

    /**
     * 生成ETH钱包地址
     */
    protected function getEthAddress(){
        return EthAddress::createAddress();
    }

    /**
     * 生成TRON钱包地址
     */
    protected function getTronAddress(){
        return TronAddress::createAddress();
    }

    /**
     * 邮件发送
     * @param $host //服务器地址
     * @param $user //登陆账号
     * @param $pass //授权码
     * @param $ssl //使用ssl加密方式登录鉴权
     * @param $port //ssl连接smtp服务器的远程服务器端口号
     * @param $email //发件人邮箱
     * @param $name //发件人名称
     * @param $to //收件人邮箱地址
     * @param $title //邮件的主题
     * @param $content //邮件的内容
    */
    protected function SendEmail($host, $user, $pass, $ssl, $port, $email, $name, $to, $title, $content){
        return Email::sendEmail($host, $user, $pass, $ssl, $port, $email, $name, $to, $title, $content);
    }



}
