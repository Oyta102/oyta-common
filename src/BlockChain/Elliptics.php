<?php

namespace Oyta\Common\BlockChain;

use Elliptic\EC;

class Elliptics
{
    public static function get_ecdsa(){
        $ec = new EC('secp256k1');
        $key = $ec->genKeyPair();
        $priv = $ec->keyFromPrivate($key->priv);
        $privateKey = $priv->getPrivate('hex');
        return [
            'privateKey'=>$privateKey
        ];
    }
}
