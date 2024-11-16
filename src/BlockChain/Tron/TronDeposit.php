<?php

namespace Oyta\Common\BlockChain\Tron;

use Oyta\Common\Share\HttpRequest;

class TronDeposit
{
    private static array $Key = array('TRON-PRO-API-KEY:c2922047-eb24-4a9a-a6b9-6f8887a4bbc1');

    public static function getQuery($address,$hour = 1800){
        $result = [];
        $time = time();
        $end    = $time * 1000;
        $start  = ($time - $hour) * 1000;
        $params = [
            'limit'           => 50,
            'start'           => 0,
            'address'         => $address,
            'start_timestamp' => $start,
            'end_timestamp'   => $end,
        ];

        //$api    = "https://nileapi.tronscan.org/api/new/transfer?" . http_build_query($params);
        $api    = "https://apilist.tronscanapi.com/api/new/transfer?" . http_build_query($params);
        $resp   = HttpRequest::getCurl($api,self::$Key);
        $data   = json_decode($resp, true);
        if (empty($data)) {
            return $result;
        }
        foreach ($data['data'] as $transfer) {
            if ($transfer['transferToAddress'] == $address && $transfer['contractRet'] == 'SUCCESS') {
                if($transfer['tokenInfo']['tokenId'] == '_' && $transfer['tokenInfo']['tokenAbbr'] == 'trx'){
                    $result[] = [
                        'tokenId'  => $transfer['tokenInfo']['tokenId'],
                        'tokenAbbr'=> $transfer['tokenInfo']['tokenAbbr'],
                        'time'     => $transfer['timestamp'] / 1000,
                        'money'    => $transfer['amount'] / 1000000,
                        'trade_id' => $transfer['transactionHash'],
                        'buyer'    => $transfer['transferFromAddress'],
                    ];
                }
            }
        }
        return $result;
    }

    public static function getTokenQuery($address, $token = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', $hour = 1800){
        $result = [];
        $time = time();
        $end    = $time * 1000;
        $start  = ($time - $hour) * 1000;
        $params = [
            'limit'           => 50,
            'start'           => 0,
            'direction'       => 'in',
            'relatedAddress'  => $address,
            'start_timestamp' => $start,
            'end_timestamp'   => $end,
        ];
        $api    = "https://apilist.tronscanapi.com/api/token_trc20/transfers?" . http_build_query($params);
        $resp   = HttpRequest::getCurl($api,self::$Key);
        $data   = json_decode($resp, true);
        if (empty($data)) {
            return $result;
        }
        foreach ($data['token_transfers'] as $transfer) {
            if ($transfer['to_address'] == $address && $transfer['finalResult'] == 'SUCCESS') {
                if($transfer['tokenInfo']['tokenId'] == $token){
                    $result[] = [
                        'tokenId'  => $transfer['tokenInfo']['tokenId'],
                        'tokenAbbr'=> $transfer['tokenInfo']['tokenAbbr'],
                        'time'     => $transfer['block_ts'] / 1000,
                        'money'    => $transfer['quant'] / 1000000,
                        'trade_id' => $transfer['transaction_id'],
                        'buyer'    => $transfer['from_address'],
                    ];
                }
            }
        }
        return $result;
    }

    public static function getHashQuery($address, $hash){
        $result = [];
        //$api = 'https://nileapi.tronscan.org/api/transaction-info?hash='.$hash;
        $api = 'https://apilist.tronscanapi.com/api/transaction-info?hash='.$hash;
        $res = HttpRequest::getCurl($api,self::$Key);
        $data = json_decode($res,true);
        if(empty($data)){
            return $result;
        }
        if($data['contractRet'] == 'SUCCESS' && $data['hash'] == $hash){
            if($data['contractData']['owner_address'] == $address){
                if(!isset($data['tokenTransferInfo'])){
                    $result = [
                        'time'=>$data['timestamp'] /1000,
                        'money'=>$data['contractData']['amount'] / 1000000,
                        'trade_id'=>$data['hash'],
                        'buyer'=>$data['contractData']['owner_address'],
                        'debit'=>$data['contractData']['to_address']
                    ];
                }else{
                    $result = [
                        'time'=>$data['timestamp'] / 1000,
                        'money'=>(float)$data['tokenTransferInfo']['amount_str'] / pow(10,$data['tokenTransferInfo']['decimals']),
                        'trade_id'=>$data['hash'],
                        'buyer'=>$data['tokenTransferInfo']['from_address'],
                        'debit'=>$data['tokenTransferInfo']['to_address']
                    ];
                }
            }
        }
        return $result;
    }

}
