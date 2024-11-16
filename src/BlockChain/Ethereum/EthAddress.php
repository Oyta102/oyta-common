<?php

namespace Oyta\Common\BlockChain\Ethereum;

use Elliptic\EC;
use kornrunner\Keccak;

class EthAddress
{
    public static function createAddress(){
        $ec = new EC('secp256k1');
        $key = $ec->genKeyPair();
        $pem = $ec->keyFromPrivate($key->priv);
        $privateKey = $pem->getPrivate('hex');
        $publicKeyHex = $pem->getPublic(false,'hex');
        $publicHex2Bin = hex2bin($publicKeyHex);
        $addressSmall = substr(Keccak::hash(substr($publicHex2Bin, 1), 256), 24);
        $hash = Keccak::hash(strtolower($addressSmall),256);
        $address = '0x';
        for($i = 0; $i < strlen($addressSmall);$i++){
            if(ord($hash[$i] >= 97)){
                $address .= strtoupper($addressSmall[$i]);
            }else{
                $address .= $addressSmall[$i];
            }
        }
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$publicKeyHex,
            'address'=>$address
        ];
    }

    public static function createMultiAddress($private){
        $ec = new EC('secp256k1');
        $pem = $ec->keyFromPrivate($private);
        $privateKey = $pem->getPrivate('hex');
        $publicKeyHex = $pem->getPublic(false,'hex');
        $publicHex2Bin = hex2bin($publicKeyHex);
        $addressSmall = substr(Keccak::hash(substr($publicHex2Bin, 1), 256), 24);
        $hash = Keccak::hash(strtolower($addressSmall),256);
        $address = '0x';
        for($i = 0; $i < strlen($addressSmall);$i++){
            if(ord($hash[$i] >= 97)){
                $address .= strtoupper($addressSmall[$i]);
            }else{
                $address .= $addressSmall[$i];
            }
        }
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$publicKeyHex,
            'address'=>$address
        ];
    }
}
