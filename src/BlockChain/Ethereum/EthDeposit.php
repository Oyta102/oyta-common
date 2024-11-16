<?php

namespace Oyta\Common\BlockChain\Ethereum;

use Oyta\Common\Share\HttpRequest;

class EthDeposit
{
    public static function getQuery($address){
        $result = [];
        $params = [
            'module'=>'account',
            'action'=>'txlist',
            'address'=>$address,
            'startblock'=>'0',
            'endblock'=>'99999999',
            'page'=>'1',
            'offset'=>'20',
            'sort'=>'desc',
            'apikey'=>'47ZWYSMI1ZD284CS8R88VBU79Q7YKE8JV8',
        ];
        $api = "https://api.etherscan.io/api?" . http_build_query($params);
        $resp   = HttpRequest::getCurl($api);
        $data   = json_decode($resp, true);
        dump($data);
        if (empty($data)) {
            return $result;
        }
        foreach ($data['result'] as $transfer) {
            if (strcasecmp($transfer['to'],$address)  == 0) {
                $result[] = [
                    'time'     => (int)$transfer['timeStamp'],
                    'money'    => (float)number_format($transfer['value'] / pow(10,18),8),
                    'trade_id' => $transfer['hash'],
                    'buyer'    => $transfer['from'],
                ];
            }
        }

        return $result;
    }

    public static function getTokenQuery($address, $token = 'USDT'){
        $result = [];

        $params = [
            'module'=>'account',
            'action'=>'tokentx',
            'contractaddress'=>EthTokens::getToken($token),
            'address'=>$address,
            'page'=>'1',
            'offset'=>'20',
            'startblock'=>'0',
            'endblock'=>'99999999',
            'sort'=>'desc',
            'apikey'=>'47ZWYSMI1ZD284CS8R88VBU79Q7YKE8JV8'
        ];
        $api = "https://api.etherscan.io/api?" . http_build_query($params);
        $resp   = HttpRequest::getCurl($api);
        $data   = json_decode($resp, true);
        if (empty($data)) {
            return $result;
        }
        foreach ($data['result'] as $transfer) {
            if (strcasecmp($transfer['to'],$address)  == 0 && strcasecmp($transfer['contractAddress'],$token) == 0) {
                $result[] = [
                    'time'     => (int)$transfer['timeStamp'],
                    'money'    => (float)$transfer['value'] / 1000000,
                    'trade_id' => $transfer['hash'],
                    'buyer'    => $transfer['from'],
                ];
            }
        }

        return $result;
    }

}
