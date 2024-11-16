<?php

namespace Oyta\Common\Share;

class Encrypt
{
    private static $method;

    private static $key;

    private static $iv;

    public function __construct()
    {
        self::$method = 'AES-128-CBC';
        self::$key = self::subKey();
        self::$iv = self::subKey();
    }

    private static function subKey(){
        $permitted_chars = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 16);
    }

    public static function encrypt($data){
        $data = json_encode($data);
        $encrypt = base64_encode(openssl_encrypt($data, self::$method, self::$key, 0, self::$iv));
        return self::$key.self::$iv.$encrypt;
    }

    public static function decrypt($data){
        $key = substr($data,0,16);
        $iv = substr($data,16,16);
        $dataCyb64 = substr($data,32);
        $json  = openssl_decrypt(base64_decode($dataCyb64), self::$method, self::$key, 0, self::$iv);
        return json_decode($json,true);
    }
}
