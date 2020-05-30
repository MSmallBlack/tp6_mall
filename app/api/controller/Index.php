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


    /**
     * 首页推荐大图
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRotationChart()
    {
        $res = (new Goods())->getRotationChart();
        return Show::success($res);
    }

    /**
     * 首页推荐分类商品
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function categoryGoodsRecommend()
    {
        $categoryIds = [7,8];
        $res = (new Goods())->categoryGoodsRecommend($categoryIds);
        return Show::success($res);
    }
}