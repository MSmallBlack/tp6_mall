<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/12
 * time   :23:55
 */

namespace app\common\lib;


class Time
{
    /**
     * 登录有效时间
     * @param $type
     * @return float|int
     */
    public static function userLoginExpiresTime(int $type) : int
    {
        $type = !in_array($type, [1, 2]) ? 2 : $type;
        if($type == 1){
            //7天
            $day = $type * 7;
        }elseif($type == 2){
            //30天
            $day = 30;
        }
        /** @var 有效时长(秒) $day */
        return $day * 24 * 3600;
    }
}