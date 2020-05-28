<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :23:46
 */

namespace app\common\model\mysql;


class Goods extends BaseModel
{

    /**
     * title search
     * @param $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        $query->where('title','like','%'.$value.'%');
    }

    /**
     * create_time search
     * @param $query
     * @param $value
     */
    public function searchCreateTimeAttr($query,$value)
    {
        $query->whereBetweenTime('create_time',$value[0],$value[1]);
    }

    /**
     * get goods paginate data
     * @param $likeKeys
     * @param $data
     * @param $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList($likeKeys, $data, $num)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        if (!empty($likeKeys)) {
            //搜索器
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        return $res->whereIn('status', [0, 1])->order($order)->paginate($num);
    }

}