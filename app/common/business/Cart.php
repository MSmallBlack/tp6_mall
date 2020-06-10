<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/9
 * time   :23:50
 */

namespace app\common\business;


use app\common\lib\Arr;
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
            Log::error('购物车数据加入redis失败');
            return FALSE;
        }
        return $res;
    }

    /**
     * 购物车列表
     * @param $userId
     * @return array
     */
    public function lists($userId)
    {
        try {
            $res = Cache::hGetAll(Key::userCart($userId));
            Log::create('购物车数据' . json_encode($res));
        } catch (Exception $e) {
            //记录日志
            Log::error('获取购物车列表数据失败');
            $res = [];
        }
        if (!$res) {
            return [];
        }
        $result = [];
        //获取skuId
        $skuIds = array_keys($res);
        $skus = (new GoodsSku())->getNormalInIds($skuIds);
        //获取id对应的price数组
        $skuIdPrice = array_column($skus, 'price', 'id');
        $skuIdSpecsValueIds = array_column($skus, 'specs_value_ids', 'id');
        $specsValues = (new SpecsValue())->dealSpecsValue($skuIdSpecsValueIds);

        foreach ($res as $key => $value) {
            $value = json_decode($value, true);
            $value['id'] = $key;
            //处理图片url
            $value['image'] = preg_match("/http:\/\//", $value['image']) ? $value['image'] : request()->domain() . $value['image'];
            $value['price'] = $skuIdPrice[$key] ?? 0;
            $value['sku'] = $specsValues[$key] ?? '暂无规格';
            $result[] = $value;
        }
        //redis中hash排序，按照create_time
        if (!empty($result)) {
            $result = Arr::arraySortByKey($result, 'create_time');
        }
        return $result;
    }

    /**
     * 删除redis中购物车数据
     * @param $userId
     * @param $id
     * @return bool
     */
    public function deleteRedis($userId, $id)
    {
        try {
            $res = Cache::hDel(Key::userCart($userId), $id);
        } catch (Exception $e) {
            Log::error("删除redis购物车数据失败");
            return FALSE;
        }
        return $res;
    }


    /**
     * 更新购物车数据
     * @param $userId
     * @param $id
     * @param $num
     * @return bool
     * @throws Exception
     */
    public function updateRedis($userId, $id, $num)
    {
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
        } catch (Exception $e) {
            Log::error('更新购物车数据时获取redis数据失败');
            return FALSE;
        }
        if ($get) {
            $get = json_decode($get, true);
            $get['num'] = $num;
        } else {
            throw new Exception('不存在该购物车的的商品');
        }
        try {
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($get));
        } catch (Exception $e) {
            Log::error('更新购物车数据时更新redis数据失败');
            return FALSE;
        }
        return $res;

    }

    /**
     * 获取购物车商品总数
     * @param $userId
     * @return int
     */
    public function getCartCount($userId)
    {
        try {
            $count = Cache::hLen(Key::userCart($userId));
        } catch (Exception $e) {
            Log::error('获取购物车商品总数redis数据失败');
            return 0;
        }
        return intval($count);
    }
}