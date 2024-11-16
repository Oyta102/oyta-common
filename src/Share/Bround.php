<?php

namespace Oyta\Common\Share;

class Bround
{
    /**
     * 随机数生成
     * @param int $num //随机数位数
     * @param string $type //随机数类型 默认小写字母
     * type类型 n-数字 s-小写字母 c-大写字母 y-特殊符号 其余随意组合 例: ns或sn sc或cs
     */
    public static function getRund($num,$type = 's'){
        $Number = '0123456789';
        $Small = 'qwertyuiopasdfghjklzxcvbnm';
        $Capital = 'QWERTYUIOPASDFGHJKLZXCVBNM';
        $Symbol = '~!@#$%^&*()_+-=[]\{}|,.<>?/*';
        $data = null;
        switch ($type){
            case 'n':
                $data = substr(str_shuffle($Number),0,$num);
                break;
            case 's':
                $data = substr(str_shuffle($Small),0,$num);
                break;
            case 'c':
                $data = substr(str_shuffle($Capital),0,$num);
                break;
            case 'y':
                $data = substr(str_shuffle($Symbol),0,$num);
                break;
            case 'nm':
            case 'mn':
                $data = substr(str_shuffle($Number.$Small),0,$num);
                break;
            case 'nc':
            case 'cn':
                $data = substr(str_shuffle($Number.$Capital),0,$num);
                break;
            case 'ny':
            case 'yn':
                $data = substr(str_shuffle($Number.$Symbol),0,$num);
                break;
            case 'sc':
            case 'cs':
                $data = substr(str_shuffle($Small.$Capital),0,$num);
                break;
            case 'sy':
            case 'ys':
                $data = substr(str_shuffle($Small.$Symbol),0,$num);
                break;
            case 'cy':
            case 'yc':
                $data = substr(str_shuffle($Capital.$Symbol),0,$num);
                break;
        }
        return $data;
    }
}
