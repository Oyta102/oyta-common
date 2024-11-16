<?php

namespace Oyta\Common\BlockChain\Ethereum;

use Oyta\Common\Share\HttpRequest;

class BscDeposit
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
            'offset'=>'10',
            'sort'=>'desc',
            'apikey'=>'RHEJIAICCDS29EHB7E19J9CF9TMWF18AWD',
        ];
        $api = "https://api.bscscan.com/api?" . http_build_query($params);
        $resp   = HttpRequest::getCurl($api);
        $data   = json_decode($resp, true);
        if (empty($data)) {
            return $result;
        }

        foreach ($data['result'] as $transfer) {
            if ($transfer['to'] == $address) {
                $result[] = [
                    'time'     => (int)$transfer['timeStamp'],
                    'money'    => (float)$transfer['value'] / 1e17,
                    'trade_id' => $transfer['hash'],
                    'buyer'    => $transfer['from'],
                ];
            }
        }

        return $result;
    }
}
