<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/12
 * time   :23:43
 */

namespace app\common\lib;


class Src
{
    /**
     * 获取登录所需的token
     * @param string $string
     * @return string
     */
    public static function getLoginToken($string)
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        return sha1($str . $string);
    }
}