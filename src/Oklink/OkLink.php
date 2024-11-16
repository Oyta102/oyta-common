<?php

namespace Oyta\Common\Oklink;

use Oyta\Common\Share\HttpRequest;

class OkLink
{
    private static $Key = array('Ok-Access-Key: 31cd7704-7521-4f22-b546-d481597cfdb4');

    private static $ApiUrl = 'https://www.oklink.com';

    /**
     * 支持的公链列表
     */
    public static function getSummary(){
        $url = self::$ApiUrl.'/api/v5/explorer/blockchain/summary';
        $res = HttpRequest::getCurl($url,self::$Key);
        return json_decode($res,true);
    }

    /**
     * 查询原生代币余额
     * @param $name //币种公链缩写 btc eth tron 等
     * @param $address //地址
     */
    public static function getBalance($name, $address){
        $url = self::$ApiUrl.'/api/v5/explorer/address/address-summary?chainShortName='.$name.'&address='.$address;
        $res = HttpRequest::getCurl($url,self::$Key);
        $res = json_decode($res,true);
        return $res['data'];
    }

    /**
     * 广播交易上链
     * @param $name //币种公链缩写 btc eth tron 等
     * @param $txId //要广播的已签名的原始交易
     */
    public static function BroadcastTrading($name, $txId){
        $url = self::$ApiUrl.'/api/v5/explorer/transaction/publish-tx';
        $data = array(
            'chainShortName'=>$name,
            'signedTx'=>$txId
        );
        array_push(self::$Key,'content-type:application/json');
        $res = HttpRequest::postCurl($url,$data,self::$Key);
        return json_decode($res,true);
    }
}
