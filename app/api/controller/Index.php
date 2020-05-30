<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/29
 * time   :22:31
 */

namespace app\api\controller;


use app\common\business\Goods;

class Index extends ApiBase
{
    public function getRotationChart()
    {
        $res = (new Goods())->getRotationChart();
        return show(config('status.success'),'ok',$res);
    }
}