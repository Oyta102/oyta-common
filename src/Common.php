<?php

namespace Oyta\Common;

use Oyta\Common\BlockChain\Bitcoin\BtcAddress;
use Oyta\Common\BlockChain\Bitcoin\BtcDeposits;
use Oyta\Common\BlockChain\Bitcoin\BtcTransfer;
use Oyta\Common\BlockChain\Ethereum\EthAddress;
use Oyta\Common\BlockChain\Ethereum\EthDeposit;
use Oyta\Common\BlockChain\Ethereum\EthTransfer;
use Oyta\Common\BlockChain\Tron\TronAddress;
use Oyta\Common\BlockChain\Tron\TronDeposit;
use Oyta\Common\BlockChain\Tron\TronTransfer;
use Oyta\Common\Share\GoogleAuthenticator;
use Oyta\Common\Share\Bround;
use Oyta\Common\Share\Email;
use Oyta\Common\Share\InviteCode;
use Oyta\Common\Share\Pass;
use Oyta\Common\Share\Rate;
use Oyta\Common\Stock\SinaStock;

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
    protected function setRound($num,$type = 's'){
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
     * 生成 BTC-Taproot钱包地址
     */
    protected function getBtcAddress(){
        return BtcAddress::createAddress();
    }

    /**
     * 通过私钥生成BTC-Taproot钱包地址
     * @param $privateKey //私钥
     */
    protected function getBtcMultiAddress($privateKey){
        return  BtcAddress::createMultiAddress($privateKey);
    }

    /**
     * 查询BTC转账记录--可做订单充值使用
     * @param $address //地址
     */
    protected function getBtcTransferRecord($address){
        return BtcDeposits::getQuery($address);
    }

    /**
     * 查询BTC铭文代币转账记录--可做订单充值使用
     * @param $address  //地址
     * @param $token    //铭文代币类型  runes brc20 src20 arc20 ordinals_nft
     * @param $tokenId  //铭文代币ID
     * 对于Runes符文，填写Rune ID
     * 对于BRC-20代币，填写代币的Inscription ID
     * 对于ARC-20代币，填写代币的Atomical ID
     * 对于SRC-20代币，填写代币名称
     * 对于Ordinals NFT，填写项目名称，注意需区分大小写
     * @param int $limit //返回数据条数 默认20条 最大100条
     */
    protected function getBtcTokenTransferRecord($address,$token,$tokenId,$limit=20){
        return BtcDeposits::getTokenQuery($address,$token,$tokenId,$limit);
    }

    /**
     * BTC转账
     */
    protected function BtcTransfer(){
        return BtcTransfer::Transfer();
    }

    /**
     * BTC铭文和代币转账
     */
    protected function BtcTokenTransfer(){
        return BtcTransfer::TokenTransfer();
    }

    /**
     * 生成ETH钱包地址
     */
    protected function getEthAddress(){
        return EthAddress::createAddress();
    }

    /**
     * 通过私钥生成ETH钱包地址
     * @param $privateKey //私钥
     */
    protected function getEthMultiAddress($privateKey){
        return EthAddress::createMultiAddress($privateKey);
    }

    /**
     * 查询ETH转账记录
     */
    protected function getEthTransferRecord($address){
        return EthDeposit::getQuery($address);
    }

    /**
     * 查询ETH代币转账记录
     * @param $address //地址
     * @param $token //代币地址
     */
    protected function getEthTokenTransferRecord($address,$token = '0xdAC17F958D2ee523a2206206994597C13D831ec7'){
        return EthDeposit::getTokenQuery($address,$token);
    }

    /**
     * ETH转账
     * @param $priv //发送者私钥
     * @param $from //发送者地址
     * @param $to //接收者(收款地址)
     * @param $money //发送金额
     */
    protected function EthTransfer($priv,$from,$to,$money){
        return EthTransfer::Transfer($priv,$from,$to,$money);
    }

    /**
     * ETH代币转账
     * @param $priv //发送者私钥
     * @param $from //发送者地址
     * @param $to //接收者(收款地址)
     * @param $money //发送金额
     * @param $token //代币合约地址
     */
    protected function EthTokenTransfer($priv,$from,$to,$money,$token){
        return EthTransfer::TokenTransfer($priv,$from,$to,$money,$token);
    }


    /**
     * 生成TRON钱包地址
     */
    protected function getTronAddress(){
        return TronAddress::createAddress();
    }

    /**
     * 通过私钥生成TRON钱包地址
     * @param $privateKey //私钥
     */
    protected function getTronMultiAddress($privateKey){
        return TronAddress::createMultiAddress($privateKey);
    }

    /**
     * 查询TRON转账记录
     * @param $address //地址
     */
    protected function getTronTransferRecord($address){
        return TronDeposit::getQuery($address);
    }

    /**
     * 查询TRON代币转账记录
     * @param $address  //地址
     * @param $token    //代币地址 默认TRC20-USDT
     */
    protected function getTronTokenTransferRecord($address,$token = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'){
        return TronDeposit::getTokenQuery($address,$token);
    }

    /**
     * 查询TRON转账HASH数据
     */
    protected function getTronHashTransferRecord($address,$hash){
        return TronDeposit::getHashQuery($address,$hash);
    }

    /**
     * TRX转账
     * @param $priv //发送者私钥
     * @param $from //发送者地址
     * @param $to   //接收者(收款地址)
     * @param $money //发送金额
     */
    protected function TronTransfer($priv,$from,$to,$money){
        return TronTransfer::Transfer($priv,$from,$to,$money);
    }

    /**
     * TRON代币转账
     * @param $priv //发送者私钥
     * @param $from //发送者地址
     * @param $to //接收者(收款地址)
     * @param $money //发送金额
     * @param $token //代币合约地址
     */
    protected function TronTokenTransfer($priv,$from,$to,$money,$token){
        return TronTransfer::TokenTransfer($priv,$from,$to,$money,$token);
    }

    /**
     * 获取新浪股票实时数据
     * @param $code //股票代码
     */
    protected function getSinaList($data){
        return SinaStock::getSinaRealTimeData($data);
    }

    /**
     * 获取新浪股票历史K线数据
     * @param $code //股票代码
     * @param $scale //时间 5,15,30,60 分钟
     * @param $datalen //数据长度 默认1023 自定义
     */
    protected function getSinaTraderHistory($code, $scale, $datalen=1023){
        return SinaStock::getSinaChartData($code, $scale, $datalen);
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

    /**
     * 设置邀请码 可支持十位数用户ID 最高9876543210
     * @param $data //用户ID 可直接生成7位数邀请码
     */
    protected function setInvite($data){
        return InviteCode::idToString($data);
    }

    /**
     * 获取邀请码中的用户ID
     * @param $data //邀请码  可直接获取到用户ID
     */
    protected function getInvite($data){
        return InviteCode::stringToId($data);
    }
}
