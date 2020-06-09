<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/9
 * time   :23:50
 */

namespace app\common\business;


use app\common\lib\Key;
use think\Exception;
use think\facade\Cache;
use think\facade\Log;

class Cart extends BusinessBase
{
    /**
     * 添加购物车数据到redis
     * @param $userId
     * @param $id
     * @param $num
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function insertRedis($userId, $id, $num)
    {
        //通过id获取商品数据
        $goodsSku = (new GoodsSku())->getNormalSkuAndGoods($id);
        if (!$goodsSku) {
            return FALSE;
        }
        //组合购物车数据
        $data = [
            'title' => $goodsSku['goods']['title'],
            'image' => $goodsSku['goods']['recommend_image'],
            'num' => $num,
            'goods_id' => $goodsSku['goods']['id'],
            'create_time' => time()
        ];
        try {
            //判断用户之前是否已经将该商品加入购物车
            $get = Cache::hGet(Key::userCart($userId), $id);
            if ($get) {
                $get = json_decode($get, true);
                Log::create('原有商品数量' . json_encode(['num' => $get['num']]));
                //更新购物车该商品数量
                $data['num'] = $get['num'] + $num;
                Log::create('现有商品数量' . json_encode(['num' => $data['num']]));
            }
            //加入redis
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($data));
        } catch (Exception $e) {
            //记录日志
            Log::error('s购物车数据加入redis失败');
            return FALSE;
        }
        return $res;
    }
}