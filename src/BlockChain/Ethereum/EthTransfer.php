<?php

namespace Oyta\Common\BlockChain\Ethereum;

use kornrunner\Ethereum\Transaction;
use kornrunner\Ethereum\Token;
use Oyta\Common\Share\HttpRequest;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\Web3;

class EthTransfer
{
    public static $mainurl = 'https://api.etherscan.io';

    //public static $mainurl = 'https://api-sepolia.etherscan.io';

    public static $mainnetWeb = 'https://mainnet.infura.io/v3/c365c01eefde491fab12bc65cbb5e8af';

    //public static $mainnetWeb = 'https://sepolia.infura.io/v3/c365c01eefde491fab12bc65cbb5e8af';

    private static $Key = '47ZWYSMI1ZD284CS8R88VBU79Q7YKE8JV8';


    public static function Transfer($privateKey,$fromAddress,$toAddress,$tokenAmount){
        $privateKey = ltrim($privateKey, "0x");
        //$fromAddress = ltrim($fromAddress, "0x");
        $toAddress = ltrim($toAddress, "0x");

        $apiurl = self::$mainurl;

        $balanuri = $apiurl.'/api?module=account&action=balance&address='.$fromAddress.'&tag=latest&apikey='.self::$Key;
        $balanres = HttpRequest::getCurl($balanuri);
        $balances = json_decode($balanres,true);
        $balance = $balances['result'];

        $sacuri = $apiurl.'/api?module=proxy&action=eth_getTransactionCount&address='.$fromAddress.'&tag=latest&apikey='.self::$Key;
        $sacres = HttpRequest::getCurl($sacuri);
        $sacres = json_decode($sacres,true);
        $noce = $sacres['result'];
        $noce = ltrim($noce, "0x");

        $gasuri = $apiurl.'/api?module=proxy&action=eth_gasPrice&apikey='.self::$Key;
        $gasres = HttpRequest::getCurl($gasuri);
        $gasres = json_decode($gasres,true);
        $gasPrice = $gasres['result'];
        $gasPrice = ltrim($gasPrice, "0x");

        $gas = dechex(21000);

        $tokenAmount = $tokenAmount * pow(10,18);
        $tokenAmount = dechex($tokenAmount);
        $nonce    = $noce;
        $gasPrice = $gasPrice;
        $gasLimit = $gas;
        $to       = $toAddress;
        $value    = $tokenAmount;

        $transaction = new Transaction ($nonce, $gasPrice, $gasLimit, $to, $value);
        $st = $transaction->getRaw($privateKey);
        $headers = array('Content-Type: application/json');
        $data = array(
            'method'=>'eth_sendRawTransaction',
            'jsonrpc'=>'2.0',
            'id'=>1,
            'params'=>['0x'.$st]
        );
        $res = HttpRequest::postCurl(self::$mainnetWeb,json_encode($data),$headers);
        $res = json_decode($res,true);
        if(!isset($res['result'])){
            return ['code'=>100,'message'=>$res['error']['message'],'txHash'=>null];
        }
        return ['code'=>200,'message'=>'ok','txHash'=>$res['result']];
    }

    public static function TokenTransfer($privateKey, $fromAddress, $toAddress, $tokenAmount, $tokenContractAddress){
        $privateKey = ltrim($privateKey, "0x");
        //$fromAddress = ltrim($fromAddress, "0x");
        $toAddress = ltrim($toAddress, "0x");

        $apiurl = self::$mainurl;
        $web3 = new Web3(new HttpProvider(self::$mainnetWeb));

        $abiuri = $apiurl.'/api?module=contract&action=getabi&address='.$tokenContractAddress.'&apikey='.self::$Key;
        $abiJson = HttpRequest::getCurl($abiuri);
        $abiJson = json_decode($abiJson,true);
        $abi = $abiJson['result'];

        $contract = new Contract($web3->provider, $abi);
        $contract->at($tokenContractAddress);

        //获取地址执行交易的数量
        $sacuri = $apiurl.'/api?module=proxy&action=eth_getTransactionCount&address='.$fromAddress.'&tag=latest&apikey='.self::$Key;
        $sacres = HttpRequest::getCurl($sacuri);
        $sacres = json_decode($sacres,true);
        $noce = $sacres['result'];
        $noce = ltrim($noce, "0x");

        //获取当前的Gas价格
        $gasuri = $apiurl.'/api?module=proxy&action=eth_gasPrice&apikey='.self::$Key;
        $gasres = HttpRequest::getCurl($gasuri);
        $gasres = json_decode($gasres,true);
        $gasPrice = $gasres['result'];
        $gasPrice = ltrim($gasPrice, "0x");

        $gas = dechex(50000);
        $nonce    = $noce;
        $gasPrice = $gasPrice;
        $gasLimit = $gas;
        $to       = $toAddress;
        $token = new Token;
        $usdt = new Token\USDT;

        $amount = (float)$tokenAmount;
        $hexAmount = $token->hexAmount($usdt, $amount);

        $data = $token->getTransferData($to, $hexAmount);

        $transaction = new Transaction($nonce, $gasPrice, $gasLimit, $usdt::ADDRESS, '', $data);
        $st = $transaction->getRaw($privateKey);

        $headers = array('Content-Type: application/json');
        $data = array(
            'method'=>'eth_sendRawTransaction',
            'jsonrpc'=>'2.0',
            'id'=>1,
            'params'=>['0x'.$st]
        );
        $res = HttpRequest::postCurl(self::$mainnetWeb,json_encode($data),$headers);
        $res = json_decode($res,true);
        if(!isset($res['result'])){
            return ['code'=>100,'message'=>$res['error']['message'],'txHash'=>null];
        }
        return ['code'=>200,'message'=>'ok','txHash'=>$res['result']];
    }
}
