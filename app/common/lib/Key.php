<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/10
 * time   :0:14
 */

namespace app\common\lib;


class Key
{
    /**
     * 用户购物车redis的key
     * @param $userId
     * @return string
     */
    public static function userCart($userId)
    {
        return config('redis.cart_pre') . $userId;
    }
}