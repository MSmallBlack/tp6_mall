<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/29
 * time   :0:22
 */

namespace app\common\lib;


class ListPage
{

    /**
     * 分页数据为空，默认返回
     * @param $num
     * @return array
     */
    public static function listIsEmpty($num)
    {
        return [
            'total' => 0,
            'per_page' => $num,
            'current_page' => 1,
            'last_page' => 0,
            'data' => []
        ];
    }
}