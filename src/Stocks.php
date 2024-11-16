<?php

namespace Oyta\Common;

use Oyta\Common\Stock\SinaStock;

class Stocks
{
    /**
     * 获取新浪股票实时数据
     * @param $code //股票代码
     */
    public static function getSinaList($data){
        return SinaStock::getSinaRealTimeData($data);
    }

    /**
     * 获取新浪股票历史K线数据
     * @param $code //股票代码
     * @param $scale //时间 5,15,30,60 分钟
     * @param $datalen //数据长度 默认1023 自定义
     */
    public static function getSinaTraderHistory($code, $scale, $datalen){
        return SinaStock::getSinaChartData($code, $scale, $datalen);
    }

}
