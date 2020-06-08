<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/28
 * time   :0:00
 */

namespace app\common\model\mysql;


class GoodsSku extends BaseModel
{

    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }


    /**
     * 获取sku数据
     * @param $goodsId
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalByGoodsId($goodsId)
    {
        $where = [
            'goods_id' => $goodsId,
            'status' => config('status.mysql.table_normal')
        ];
        return $this->where($where)->select();
    }
}