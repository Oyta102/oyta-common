<?php

namespace Oyta\Common\BlockChain\Tron;

use Elliptic\EC;
use kornrunner\Keccak;
use Oyta\Common\Share\Base58;

class TronAddress
{
    public static function createAddress(){
        $ec = new EC('secp256k1');
        $key = $ec->genKeyPair();
        $priv = $ec->keyFromPrivate($key->priv);
        $privateKey = $priv->getPrivate('hex');
        $pubKeyHex = $priv->getPublic(false, "hex");
        $pubKeyBin = hex2bin($pubKeyHex);
        if(strlen($pubKeyBin) == 65){
            $pubKeyBin = substr($pubKeyBin,1);
        }
        $addressHex = '41'.substr(Keccak::hash($pubKeyBin,256),24);
        $addressB58 = Base58::hexString2Base58check($addressHex);
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$pubKeyHex,
            'addressHex'=>$addressHex,
            'address'=>$addressB58
        ];
    }

    public static function createMultiAddress($private){
        $ec = new EC('secp256k1');
        $priv = $ec->keyFromPrivate($private);
        $privateKey = $priv->getPrivate('hex');
        $pubKeyHex = $priv->getPublic(false, "hex");
        $pubKeyBin = hex2bin($pubKeyHex);
        if(strlen($pubKeyBin) == 65){
            $pubKeyBin = substr($pubKeyBin,1);
        }
        $addressHex = '41'.substr(Keccak::hash($pubKeyBin,256),24);
        $addressB58 = Base58::hexString2Base58check($addressHex);
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$pubKeyHex,
            'addressHex'=>$addressHex,
            'address'=>$addressB58
        ];
    }
}
