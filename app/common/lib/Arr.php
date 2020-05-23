<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/24
 * time   :1:01
 */

namespace app\common\lib;


class Arr
{
    /**
     * 分类树  无限极
     * @param $data
     * @return array
     */
    public static function getTree($data)
    {
        $items = [];
        foreach ($data as $value) {
            $items[$value['category_id']] = $value;
        }
        $tree = [];
        foreach ($items as $id => $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['list'][] = &$items[$id];
            } else {
                $tree[] = &$items[$id];
            }
        }
        return $tree;
    }
}