<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/22
 * time   :1:07
 */

namespace app\common\lib;


class Status
{

    /**
     * 获取表状态值
     * @return array
     */
    public static function getTableStatus()
    {
        $mysqlStatus = config('status.mysql');
        return array_values($mysqlStatus);
    }
}