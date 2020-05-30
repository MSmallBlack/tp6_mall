<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/29
 * time   :22:31
 */

namespace app\api\controller;


use app\common\business\Goods;
use app\common\lib\Show;

class Index extends ApiBase
{
    public function getRotationChart()
    {
        $res = (new Goods())->getRotationChart();
        return Show::success($res);
    }
}