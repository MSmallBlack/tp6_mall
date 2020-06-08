<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/8
 * time   :22:48
 */

namespace app\api\controller\mall;


use app\api\controller\ApiBase;
use app\common\business\Goods;
use app\common\lib\Show;

class Detail extends ApiBase
{
    public function index()
    {
        $id = input('param.id', 0, 'intval');
        if (!$id) {
            return Show::error();
        }
        $res = (new Goods())->getGoodsDetailBySkuId($id);
        if (!$res) {
            return Show::error();
        }
        return Show::success($res);
    }
}