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


    /**
     * 三级分类树
     * @param $data
     * @param int $firstCount   一级
     * @param int $secondCount  二级
     * @param int $threeCount   三级
     * @return array
     */
    public static function sliceTreeArr($data,$firstCount = 5,$secondCount = 3,$threeCount = 5)
    {
        $data = array_slice($data,0,$firstCount);
        foreach($data as $key => $value){
            if(!emmpty($value['list'])){
                $data[$key]['list'] = array_slice($value['list'],0,$secondCount);
                foreach($value['list'] as $key1 => $value1){
                    if(!empty($value1['list'])){
                        $data[$key]['list'][$key1]['list'] = array_slice($value1['list'],0,$threeCount);
                    }
                }
            }
        }
        return $data;
    }
}