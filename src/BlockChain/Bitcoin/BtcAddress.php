<?php

namespace Oyta\Common\BlockChain\Bitcoin;

use AndKom\Bitcoin\Address\Output\OutputFactory;
use AndKom\Bitcoin\Address\Taproot;
use Elliptic\EC;

class BtcAddress
{
    public static function createAddress(){
        $ec = new EC('secp256k1');
        $key = $ec->genKeyPair();
        $pem = $ec->keyFromPrivate($key->priv);
        $privateKey = $pem->getPrivate('hex');
        $publicKeyHash = $pem->getPublic(false,'hash');
        $publicKeyHex = $pem->getPublic(false,'hex');
        $publicHex2Bin = hex2bin($publicKeyHex);
        $taprootPublicKey = Taproot::construct($publicHex2Bin);
        $p2tr = OutputFactory::p2tr($taprootPublicKey)->address();
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$publicKeyHex,
            'address'=>$p2tr
        ];
    }

    public static function createMultiAddress($private){
        $ec = new EC('secp256k1');
        $pem = $ec->keyFromPrivate($private);
        $privateKey = $pem->getPrivate('hex');
        $publicKeyHash = $pem->getPublic(false,'hash');
        $publicKeyHex = $pem->getPublic(false,'hex');
        $publicHex2Bin = hex2bin($publicKeyHex);
        $taprootPublicKey = Taproot::construct($publicHex2Bin);
        $p2tr = OutputFactory::p2tr($taprootPublicKey)->address();
        return [
            'privateKey'=>$privateKey,
            'publicKey'=>$publicKeyHex,
            'address'=>$p2tr
        ];
    }
}
