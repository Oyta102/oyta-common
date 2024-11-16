<?php

namespace Oyta\Common\BlockChain;

use Elliptic\EC;

class Elliptics
{
    public static function get_ecdsa(){
        $ec = new EC('secp256k1');
        // Generate keys
        $key = $ec->genKeyPair();
        $pem = $ec->keyFromPublic($key->priv);
        return [
          'privateKey'=>$pem->getPrivate('hex')
        ];
    }
}
