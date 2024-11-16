<?php

namespace Oyta\Common\BlockChain\Bitcoin;

use Oyta\Common\Share\HttpRequest;

class BtcDeposits
{
    private static array $Key = array('Ok-Access-Key: 31cd7704-7521-4f22-b546-d481597cfdb4');

    public static function getQuery($address, $limit = 20){
        $url = 'https://www.oklink.com/api/v5/explorer/address/transaction-list?chainShortName=BTC&address='.$address.'&limit='.$limit;
        $response = HttpRequest::getCurl($url,self::$Key);
        $json = json_decode($response,true);
        $data = $json['data'][0]['transactionLists'];
        $res = [];
        if(count($data) > 0){
            foreach ($data as $v){
                if($v['to'] == $address && $v['state'] == 'success'){
                    $res[] = [
                        'tokenAbbr'=>$v['transactionSymbol'],
                        'txId'=>$v['txId'],
                        'buyer'=>$v['from'],
                        'money'=>$v['amount'],
                        'time'=>$v['transactionTime']/1000,
                        'state'=>$v['state']
                    ];
                }
            }
        }
        return $res;
    }

    public static function getTokenQuery($address, $token, $tokenId, $limit = 20){
        $url = 'https://www.oklink.com/api/v5/explorer/inscription/address-token-transaction-list?chainShortName=btc&protocolType='.$token.'&tokenInscriptionId='.$tokenId.'&address='.$address.'&limit='.$limit;
        $response =  HttpRequest::getCurl($url,self::$Key);
        $json = json_decode($response,true);
        $data = $json['data'][0]['transactionList'];
        $res = [];
        if(count($data) > 0){
            foreach ($data as $v){
                if($v['to'] == $address && $v['state'] == 'success'){
                    if($v['tokenInscriptionId'] == $tokenId && $v['action'] == 'transfer'){
                        $res[] = [
                            'tokenType'=>$v['protocolType'],
                            'tokenAbbr'=>$v['symbol'],
                            'txId'=>$v['txId'],
                            'buyer'=>$v['from'],
                            'money'=>$v['amount'],
                            'time'=>$v['transactionTime']/1000,
                            'state'=>$v['state']
                        ];
                    }
                }
            }
        }
        return $res;
    }

}
