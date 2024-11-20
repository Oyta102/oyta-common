<?php

namespace Oyta\Common\Share;

class InviteCode
{
    private static int $init_num = 123456789;
    private static string $baseChar = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
    private static string $type = '32';

    private static function AidToString($num): string
    {
        $str = '';
        while ($num!=0){
            $tmp = $num % self::$type;
            $str .= self::$baseChar[$tmp];
            $num = intval($num/self::$type);
        }

        return $str;
    }

    public static function idToString($id){//10位内id 返回7位字母数字
        //数组 增加备用数值
        $id += self::$init_num;

        //左补0 补齐10位
        $str = str_pad($id,10,'0',STR_PAD_LEFT);

        //按位 拆分 4 6位（32进制 4 6位划分）
        $num1 = intval($str[0].$str[2].$str[6].$str[9]);
        $num2 = intval($str[1].$str[3].$str[4].$str[5].$str[7].$str[8]);

        $str1 = self::AidToString($num1);
        $str1 = strrev($str1);

        $str2 = self::AidToString($num2);
        $str2 = strrev($str2);

        //4 补足 3 4位 U L
        return str_pad($str1,3,'U',STR_PAD_RIGHT).str_pad($str2,4,'L',STR_PAD_RIGHT);
    }

    public static function stringToId($str){
        //1 清除 3 4 位补足位
        $str1 = trim(substr($str,0,3),'U');
        $str2 = trim(substr($str,3,4),'L');

        $num1 = self::AstringToId($str1);
        $num2 = self::AstringToId($str2);
        //补位拼接
        $str1 = str_pad($num1,4,'0',STR_PAD_LEFT);
        $str2 = str_pad($num2,6,'0',STR_PAD_LEFT);
        $id = ltrim($str1[0].$str2[0].$str1[1].$str2[1].$str2[2].$str2[3].$str1[2].$str2[4].$str2[5].$str1[3],'0');
        //减去 备用数值
        $id -= self::$init_num;
        return $id;
    }

    private static function AstringToId($str){
        //转换为数组
        $charArr = array_flip(str_split(self::$baseChar));
        $num = 0;
        for ($i=0;$i<=strlen($str)-1;$i++)
        {
            $linshi = substr($str,$i,1);
            if(!isset($charArr[$linshi])){
                return '';
            }
            $num += $charArr[$linshi]*pow(self::$type,strlen($str)-$i-1);
        }

        return $num;
    }
}
