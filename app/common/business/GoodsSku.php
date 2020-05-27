<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :23:55
 */

namespace app\common\business;

use app\common\model\mysql\GoodsSku as GoodsSkuModel;
use think\Exception;


/**
 * 商品sku
 */
class GoodsSku extends BusinessBase
{

    public $model = null;

    public function __construct()
    {
        $this->model = new GoodsSkuModel();
    }


    /**
     * insert batch sku data
     * @param $data
     * @return array|bool
     * @throws \Exception
     */
    public function saveAll($data)
    {
        if (!$data['skus']) {
            return false;
        }
        foreach ($data['skus'] as $value) {
            $insertData[] = [
                'goods_id' => $data['goods_id'],
                'specs_value_ids' => $value['propvalnames']['propvalids'],
                'price' => $value['propvalnames']['skuSellPrice'],
                'cost_price' => $value['propvalnames']['skuMarketPrice'],
                'stock' => $value['propvalnames']['skuStock']
            ];
        }

        try {
            $result = $this->model->saveAll($insertData);
            return $result->toArray();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

}