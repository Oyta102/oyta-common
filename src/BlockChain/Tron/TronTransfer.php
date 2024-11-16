<?php

namespace Oyta\Common\BlockChain\Tron;

use kornrunner\Secp256k1;
use Oyta\Common\Share\Base58;
use Oyta\Common\Share\HttpRequest;

class TronTransfer
{
    private static $Apiurl = 'https://api.trongrid.io'; //正式环境
    //private static $Apiurl = 'https://api.nileex.io'; //测试环境

    private static $Apiuri = 'https://apilist.tronscanapi.com'; //正式环境
    //private static $Apiuri = 'https://nileapi.tronscan.org'; //测试环境

    private static $headers = array('Content-Type: application/json');

    private static function balan($address){
        $url = self::$Apiuri.'/api/accountv2?address='.$address;
        $res = HttpRequest::getCurl($url);
        $data   = json_decode($res, true);
        $trcs = '0';
        foreach($data['withPriceTokens'] as $v){
            if($v['tokenId'] == '_'){
                $trcs = $v['amount'];
            }
        }
        $list = [
            'freeNet'=>$data['bandwidth']['freeNetRemaining'],
            'energy'=>$data['bandwidth']['energyRemaining'],
            'trx'=>(float)$trcs
        ];
        return $list;
    }

    private static function checkWalletActivation($address){
        $url = self::$Apiurl.'/wallet/getaccount';
        $data = array(
            'address'=>$address
        );
        $res = HttpRequest::postCurl($url,json_encode($data),self::$headers);
        $res = json_decode($res,true);
        return !empty($res['address']);
    }

    private static function getTransactionFee($contractAddress){
        $url = self::$Apiurl.'/wallet/gettransactionfee';
        $data = array(
            'contract_address' => $contractAddress
        );
        $result = HttpRequest::postCurl($url,json_encode($data),self::$headers);
        return json_decode($result,true);
    }

    private static function checkBalanceAndFee($sourceAddress, $amountToCollect, $transactionFee){
        $address = Base58::hexString2Base58check($sourceAddress);
        $url = self::$Apiuri.'/api/accountv2?address='.$address;
        $res = HttpRequest::getCurl($url);
        $result = json_decode($res, true);
        $energy = $result['bandwidth']['freeNetRemaining'];
        $trxBalance = $result['balance'];
        return ($energy > 30000 || $trxBalance >= $transactionFee + $amountToCollect);
    }

    private static function getTokenDecimals($contractAddress,$sourceAddress){
        $url = self::$Apiurl.'/wallet/triggerconstantcontract';
        $data = array(
            'contract_address' => $contractAddress,
            'owner_address'=>$sourceAddress,
            'function_selector' => 'decimals()',
            'parameter' =>'',
        );
        $result = HttpRequest::postCurl($url,json_encode($data),self::$headers);
        $result = json_decode($result,true);
        return hexdec($result['constant_result'][0]);
    }

    private static function getContractTokenBalance($contractAddress, $sourceAddress){
        $url = self::$Apiurl.'/wallet/triggerconstantcontract';
        $sourceAddressWithoutPrefix = substr($sourceAddress, 2);
        $paddedAddress = str_pad($sourceAddressWithoutPrefix, 64, '0', STR_PAD_LEFT);
        $data = array(
            'contract_address' => $contractAddress,
            'function_selector' => 'balanceOf(address)',
            'parameter' => $paddedAddress,
            'owner_address'=>$sourceAddress
        );
        $result = HttpRequest::postCurl($url,json_encode($data),self::$headers);
        $result = json_decode($result,true);
        $balan = $result['constant_result'][0];
        $hexam = hexdec($balan);
        $decim = self::getTokenDecimals($contractAddress, $sourceAddress);
        $prec = $hexam/(10 ** $decim);
        return (int)$prec;
    }

    private static function getSign($message, $privateKey){
        $secp = new Secp256k1();
        $sign = $secp->sign($message,$privateKey,['canonical' => false]);
        return $sign->toHex() . bin2hex(implode('', array_map('chr', [$sign->getRecoveryParam()])));
    }

    private static function BroAdcast($data,$privateKey){
        $url = self::$Apiurl.'/wallet/broadcasttransaction';
        $bro = array(
            'signature'=>self::getSign($data['txID'],$privateKey),
            'raw_data'=>$data['raw_data'],
            'raw_data_hex'=>$data['raw_data_hex']
        );
        $res = HttpRequest::postCurl($url,json_encode($bro),self::$headers);
        $res = json_decode($res,true);
        if(isset($res['result'])){
            return ['code'=>200,'message'=>'交易广播成功','txHash'=>$res['txid']];
        }
    }

    public static function Transfer($private_key, $sourceAddress, $to_address, $amountToCollect){
        $balan = self::balan($sourceAddress);
        if($balan['trx'] < $amountToCollect){
            return ['code'=>100,'message'=>'TRX余额不足','data'=>null];
        }
        $to_address = Base58::base58check2HexString($to_address);
        $sourceAddress = Base58::base58check2HexString($sourceAddress);
        $tomber = $amountToCollect * pow(10,6);
        $data = array(
            'to_address'=>$to_address,
            'owner_address' => $sourceAddress,
            'amount' => $tomber
        );

        $url = self::$Apiurl.'/wallet/createtransaction';
        $result = HttpRequest::postCurl($url,json_encode($data),self::$headers);
        $result = json_decode($result,true);
        if($result['txID']){
            return self::BroAdcast($result,$private_key);
        }else{
            return ['code'=>100,'message'=>$result['message'],'txHash'=>null];
        }
    }

    public static function TokenTransfer($privateKey, $sourceAddress, $destinationAddress, $amountToCollect, $contractAddress){
        $contractAddress = Base58::base58check2HexString($contractAddress);
        $sourceAddress = Base58::base58check2HexString($sourceAddress);
        $destinationAddress = Base58::base58check2HexString($destinationAddress);
        if (!self::checkWalletActivation($sourceAddress)) {
            return ['code'=>100,'message'=>'钱包地址未激活','data'=>Base58::hexString2Base58check($sourceAddress)];
        }
        $feeResult = self::getTransactionFee($contractAddress);
        $bandwidthFee = $feeResult['bandwidth'] ?? 0;
        $energyFee = $feeResult['energy'] ?? 0;
        $transactionFee = $bandwidthFee + $energyFee;
        if(!self::checkBalanceAndFee($sourceAddress,$amountToCollect,$transactionFee)){
            return ['code'=>100,'message'=>'钱包地址能量或TRX余额不足','data'=>Base58::hexString2Base58check($sourceAddress)];
        }
        $tokenBalance = self::getContractTokenBalance($contractAddress, $sourceAddress);
        if ($tokenBalance < $amountToCollect) {
            return ['code'=>100,'message'=>'钱包地址代币余额不足','data'=>Base58::hexString2Base58check($sourceAddress)];
        }
        $sourceAdd = substr($destinationAddress, 2);
        $toFormat = str_pad($sourceAdd, 64, '0', STR_PAD_LEFT);
        $decim = self::getTokenDecimals($contractAddress,$sourceAddress);
        $tomber = dechex($amountToCollect * pow(10,$decim));
        $numberFormat = str_pad($tomber, 64, '0', STR_PAD_LEFT);
        $data = array(
            'contract_address' => $contractAddress, // TRC-20代币合约地址
            'owner_address' => $sourceAddress,  // 钱包地址
            'function_selector' => 'transfer(address,uint256)',
            'parameter' => $toFormat.$numberFormat,
            'fee_limit' => 100000000,
            'call_value' => 0,
        );
        $url = self::$Apiurl.'/wallet/triggersmartcontract';
        $result = HttpRequest::postCurl($url,json_encode($data),self::$headers);
        $result = json_decode($result,true);
        if(isset($result['result']['result'])){
            return self::BroAdcast($result['transaction'],$privateKey);
        }else{
            return ['code'=>100,'message'=>$result['result']['message'],'txHash'=>null];
        }
    }
}
