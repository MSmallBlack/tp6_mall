<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :23:37
 */

namespace app\common\business;


use app\common\model\mysql\Goods as GoodsModel;
use think\Exception;

class Goods extends BusinessBase
{

    public $model = null;

    public function __construct()
    {
        $this->model = new GoodsModel();
    }


    /**
     * insert goods data
     * @param $data
     * @return bool|int
     * @throws Exception
     */
    public function insertData($data)
    {
        //insert to goods
        $goodsId = $this->add($data);
        if (!$goodsId) {
            return $goodsId;
        }
        //insert to sku
        if ($data['goods_specs_type'] == 1) {   // 单一规格
            $goodsSkuData = [
                'goods_id' => $goodsId
            ];
            return true;
        } else {     //多规格
            $goodsSkuBusiness = new GoodsSku();
            $data['goods_id'] = $goodsId;
            //insert batch sku
            $res = $goodsSkuBusiness->saveAll($data);
            if (!empty($res)) {
                //总的库存
                $stock = array_sum(array_column($res, 'stock'));
                //update goods data
                $goodsUpdateData = [
                    'price' => $res[0]['price'],
                    'cost_price' => $res[0]['cost_price'],
                    'stock' => $stock,
                    'sku_id' => $res[0]['id']
                ];
                $goodsRes = $this->model->updateById($goodsId, $goodsUpdateData);
                if (!$goodsRes) {
                    throw new Exception('goods表更新失败');
                }
            } else {
                throw new Exception('sku新增失败');
            }
            return true;


        }

    }
}