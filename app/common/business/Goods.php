<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :23:37
 */

namespace app\common\business;


use app\common\lib\ListPage;
use app\common\model\mysql\Goods as GoodsModel;
use think\Exception;
use think\facade\Log;



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
     * @throws \Exception
     */
    public function insertData($data)
    {
        //开启事务
        $this->model->startTrans();
        try {
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
                        Log::create('sku新增成功,goods表更新失败');
                        throw new Exception('goods表更新失败');
                    }
                } else {
                    Log::create('sku新增失败');
                    throw new Exception('sku新增失败');
                }
            }
            //事务提交
            $this->model->commit();
            return true;
        }catch (Exception $e){
            //写入日志
            Log::create('事务回滚，商品新增失败');
            //回滚
            $this->model->rollback();
            return false;
        }

    }


    /**
     * 获取分页数据
     * @param $data
     * @param $num
     * @return array
     */
    public function getList($data,$num)
    {
        //检索
        $likeKeys = [];
        if(!empty($data)){
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getList($likeKeys,$data,$num);
            $res = $list->toArray();
        }catch (Exception $e){
            //数据为空时的返回
            $res = ListPage::listIsEmpty($num);
        }
        return $res;
    }


    /**
     * 首页大图
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRotationChart()
    {
        $data = [
            'is_index_recommend' => 1
        ];
        $field = "sku_id as id,title,big_image as image";
        try {
            $res = $this->model->getNormalGoodsCondition($data,$field);

        }catch (Exception $e){
            $res = [];
        }
        return $res->toArray();
    }
}