<?php

namespace Oyta\Common\Share;

class Rate
{
    public static function exchange($data){
        $url = 'https://latest.currency-api.pages.dev/v1/currencies/'.$data.'.json';
        $response = HttpRequest::geturl($url);
        $json = json_decode($response,true);
        return $json[$data];
    }
}
