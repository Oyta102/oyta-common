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
        $address = '0x'.substr(Keccak::hash($publicHex2Bin,256),24);
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$publicKeyHex,
            'address'=>$address
        ];
    }

    public static function createMultiAddress($privateKey){
        $ec = new EC('secp256k1');
        $pem = $ec->keyFromPrivate($privateKey);
        $privateKey = $pem->getPrivate('hex');
    }
}
