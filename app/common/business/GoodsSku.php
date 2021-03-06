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

    /**
     * 获取数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalSkuAndGoods($id)
    {
        try {
            $res = $this->model->with('goods')->find($id);
        } catch (Exception $e) {
            return [];
        }

        if (!$res) {
            return [];
        }
        $res = $res->toArray();
        if ($res['status'] !== config('status.mysql.table_normal')) {
            return [];
        }
        return $res;
    }

    /**
     * 通过goodsId获取sku数据
     * @param $goodsId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSkusByGoodsId($goodsId)
    {
        if (!$goodsId) {
            return [];
        }
        try {
            //获取sku数据
            $sku = $this->model->getNormalByGoodsId($goodsId);
        } catch (Exception $e) {
            return [];
        }
        return $sku->toArray();

    }


    public function getNormalInIds($ids)
    {
        try {
            $result = $this->model->getNormalInIds($ids);

        }catch (Exception $e){
            return [];
        }
        return $result->toArray();
    }

}