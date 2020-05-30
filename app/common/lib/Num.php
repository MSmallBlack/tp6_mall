<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/9
 * time   :0:34
 */

namespace app\common\lib;


class Num
{
    /**
     * 短信验证码位数
     * @param int $len
     * @return int
     */
    public static function getCode($len = 4)
    {
        $code = rand(1000, 9999);
        if ($len == 6) {
            $code = rand(100000, 999999);
        }
        return $code;
    }
}