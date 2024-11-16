<?php

namespace Oyta\Common\BlockChain\Ethereum;

use kornrunner\Ethereum\Token;

class EthTokens
{
    public static function getToken($data){
        $token = '';
        switch ($data){
            case 'AE':
            case 'ae':
                $token = new Token\AE;
                break;
            case 'BAT':
            case 'bat':
                $token = new Token\BAT;
                break;
            case 'BNB':
            case 'bnb':
                $token = new Token\BNB;
                break;
            case 'BTM':
            case 'btm':
                $token = new Token\BTM;
                break;
            case 'BTMX':
            case 'btmx':
                $token = new Token\BTMX;
                break;
            case 'CENNZ':
            case 'cennz':
                $token = new Token\CENNZ;
                break;
            case 'CRO':
            case 'cro':
                $token = new Token\CRO;
                break;
            case 'DAI':
            case 'dai':
                $token = new Token\DAI;
                break;
            case 'DGD':
            case 'dgd':
                $token = new Token\DGD;
                break;
            case 'DX':
            case 'dx':
                $token = new Token\DX;
                break;
            case 'ENJ':
            case 'enj':
                $token = new Token\ENJ;
                break;
            case 'HEDG':
            case 'hedg':
                $token = new Token\HEDG;
                break;
            case 'HOT':
            case 'hot':
                $token = new Token\HOT;
                break;
            case 'HT':
            case 'ht':
                $token = new Token\HT;
                break;
            case 'ICX':
            case 'icx':
                $token = new Token\ICX;
                break;
            case 'INB':
            case 'inb':
                $token = new Token\INB;
                break;
            case 'INO':
            case 'ino':
                $token = new Token\INO;
                break;
            case 'IOST':
            case 'iost':
                $token = new Token\IOST;
                break;
            case 'KICK':
            case 'kick':
                $token = new Token\KICK;
                break;
            case 'KNC':
            case 'knc':
                $token = new Token\KNC;
                break;
            case 'LINK':
            case 'link':
                $token = new Token\LINK;
                break;
            case 'MANA':
            case 'mana':
                $token = new Token\MANA;
                break;
            case 'MATIC':
            case 'matic':
                $token = new Token\MATIC;
                break;
            case 'MCO':
            case 'mco':
                $token = new Token\MCO;
                break;
            case 'MKR':
            case 'mkr':
                $token = new Token\MKR;
                break;
            case 'MOF':
            case 'mof':
                $token = new Token\MOF;
                break;
            case 'NEXO':
            case 'nexo':
                $token = new Token\NEXO;
                break;
            case 'NOAH':
            case 'noah':
                $token = new Token\NOAH;
                break;
            case 'OKB':
            case 'okb':
                $token = new Token\OKB;
                break;
            case 'OMG':
            case 'omg':
                $token = new Token\OMG;
                break;
            case 'PAX':
            case 'pax':
                $token = new Token\PAX;
                break;
            case 'QNT':
            case 'qnt':
                $token = new Token\QNT;
                break;
            case 'REP':
            case 'rep':
                $token = new Token\REP;
                break;
            case 'RLC':
            case 'rlc':
                $token = new Token\RLC;
                break;
            case 'SAI':
            case 'sai':
                $token = new Token\SAI;
                break;
            case 'Seele':
            case 'seele':
                $token = new Token\Seele;
                break;
            case 'SNT':
            case 'snt':
                $token = new Token\SNT;
                break;
            case 'SNX':
            case 'snx':
                $token = new Token\SNX;
                break;
            case 'SXP':
            case 'sxp':
                $token = new Token\SXP;
                break;
            case 'THETA':
            case 'theta':
                $token = new Token\THETA;
                break;
            case 'TUSD':
            case 'tusd':
                $token = new Token\TUSD;
                break;
            case 'USDC':
            case 'usdc':
                $token = new Token\USDC;
                break;
            case 'USDT':
            case 'usdt':
                $token = new Token\USDT;
                break;
            case 'VEM':
            case 'vem':
                $token = new Token\VEN;
                break;
            case 'XIN':
            case 'xin':
                $token = new Token\XIN;
                break;
            case 'ZB':
            case 'zb':
                $token = new Token\ZB;
                break;
            case 'ZIL':
            case 'zil':
                $token = new Token\ZIL;
                break;
            case 'ZRX':
            case 'zrx':
                $token = new Token\ZRX;
                break;
        }
        $address = $token::ADDRESS;
        return $address;
    }
}
