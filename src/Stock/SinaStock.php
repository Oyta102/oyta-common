<?php

namespace Oyta\Common\Stock;

use Oyta\Common\Share\HttpRequest;

class SinaStock
{
    /**
     * 获取新浪股票实时数据
     * @param $code //股票代码
     */
    public static function getSinaRealTimeData($code){
        $data = self::SpecialGetData($code);
        $data = explode(',',mb_convert_encoding($data, 'utf-8', 'gbk'));
        preg_match('/var hq_str_([a-zA-Z]+)([0-9]+)="(.*?)$/', $data[0], $matches);
        $sands = [$matches[1],$matches[2],$matches[3]];
        array_splice($data,1,0,$sands);
        return self::SuberData($data);
    }

    /**
     * 获取新浪股票历史K线数据
     * @param $code //股票代码
     * @param $scale //时间 5,15,30,60 分钟
     * @param $datalen //数据长度 默认1023 自定义
     */
    public static function getSinaChartData($code, $scale, $datalen = 1023){
        $url='https://quotes.sina.cn/cn/api/json_v2.php/CN_MarketDataService.getKLineData?symbol='.$code.'&scale='.$scale.'&ma=no&datalen='.$datalen;
        $data = HttpRequest::getCurl($url);
        $data = json_decode($data,true);
        return $data;
    }



    /**
     * 新浪实时数据转义
     */
    private static function SuberData($data){
        return [
            'name'=>$data[3],   //股票名称
            'pre'=>$data[1],    //前缀
            'code'=>$data[2],   //股票代码
            'date'=>$data[33],  //日期
            'time'=>$data[34],   //时间
            'open'=>$data[4],   //今日开盘价
            'close'=>$data[5],  //昨日收盘价
            'price'=>$data[6],  //当前价格
            'high'=>$data[7],   //今日最高价
            'low'=>$data[8],    //今日最低价
            'bid'=>$data[9],    //竞买价,即“买一”报价
            'ask'=>$data[10],   //竞卖价,即“卖一”报价
            'volume'=>$data[11],//成交的股票数，由于股票交易以一百股为基本单位，所以在使用时，通常把该值除以一百
            'amount'=>$data[12],//成交金额，单位为“元”，为了一目了然，通常以“万元”为成交金额的单位，所以通常把该值除以一万
            'vb1'=>$data[13],   //买一量
            'pb1'=>$data[14],   //买一价
            'vb2'=>$data[15],   //买二量
            'pb2'=>$data[16],   //买二价
            'vb3'=>$data[17],   //买三量
            'pb3'=>$data[18],   //买三价
            'vb4'=>$data[19],   //买四量
            'pb4'=>$data[20],   //买四价
            'vb5'=>$data[21],   //买五量
            'pb5'=>$data[22],   //买五价
            'vs1'=>$data[23],   //卖一量
            'ps1'=>$data[24],   //卖一价
            'vs2'=>$data[25],   //卖二量
            'ps2'=>$data[26],   //卖二价
            'vs3'=>$data[27],   //卖三量
            'ps3'=>$data[28],   //卖三价
            'vs4'=>$data[29],   //卖四量
            'ps4'=>$data[30],   //卖四价
            'vs5'=>$data[31],   //卖五量
            'ps5'=>$data[32],   //卖五价
        ];
    }

    /**
     * 新浪数据获取特定请求方式
     */
    private static function SpecialGetData($data){
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://hq.sinajs.cn/list=".$data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Referer: https://finance.sina.com.cn/realstock/company/".$data."/nc.shtml",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
