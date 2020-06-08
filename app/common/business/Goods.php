<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :23:37
 */

namespace app\common\business;


use AlibabaCloud\Emr\V20160408\ReleaseETLJob;
use app\common\lib\ListPage;
use app\common\model\mysql\Goods as GoodsModel;
use think\Exception;
use think\facade\Cache;
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
        } catch (Exception $e) {
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
    public function getList($data, $num)
    {
        //检索
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getList($likeKeys, $data, $num);
            $res = $list->toArray();
        } catch (Exception $e) {
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
            $res = $this->model->getNormalGoodsCondition($data, $field);

        } catch (Exception $e) {
            $res = [];
        }
        return $res->toArray();
    }


    /**
     * 获取商品分类下的数据
     * @param $categoryId
     * @return array
     */
    public function getNormalGoodsFindInSetCategoryId($categoryId)
    {
        $field = "sku_id as id,title,price,category_id,recommend_image as image";
        try {
            $res = $this->model->getNormalGoodsFindInSetCategoryId($categoryId, $field);
        } catch (Exception $e) {
            Log::create('分类商品为空');
            return [];
        }
        return $res->toArray();

    }

    /**
     * 获取商品分类数据
     * @param $categoryIds
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function categoryGoodsRecommend($categoryIds)
    {
        $categoryBusiness = new Category();
        if (!$categoryIds) {
            return [];
        }
        $res = [];
        foreach ($categoryIds as $key => $categoryId) {
            $res[$key]['categorys'] = $categoryBusiness->getFirstAndSecondLevelCategoryById($categoryId);
            $res[$key]['goods'] = $this->getNormalGoodsFindInSetCategoryId($categoryId);
        }
        return $res;
    }

    /**
     *  获取商品分类数据
     * @param $data
     * @param int $pageSize
     * @param array $order
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getNormalLists($data, $pageSize = 10, $order)
    {
        try {
            $field = 'sku_id as id,title,recommend_image as image,price';
            $list = $this->model->getNormalLists($data, $pageSize, $field, $order);
            $res = $list->toArray();
            $result = [
                'total_page_num' => $res['last_page'] ?? 0,
                'count' => $res['total'] ?? 0,
                'page' => $res['current_page'] ?? 0,
                'page_size' => $pageSize,
                'list' => $res['data'] ?? []
            ];

        } catch (Exception $e) {
            Log::create('');
            return [];
        }
        return $result;
    }


    /**
     * 获取商品详情
     * @param $skuId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsDetailBySkuId($skuId)
    {
        //sku_id  sku表=>goods_id goods表  =>  sku  数据
        $goodsSku = (new GoodsSku())->getNormalSkuAndGoods($skuId);
        if (!$goodsSku) {
            return [];
        }
        if (empty($goodsSku['goods'])) {
            //goods数据为空
            Log::error('sku为空');
            return [];
        }
        $goods = $goodsSku['goods'];
        $skus = (new GoodsSku())->getSkusByGoodsId($goods['id']);
        if (!$skus) {
            return [];
        }
        $flagValue = '';
        foreach ($skus as $value){
            if($value['id'] == $skuId){
                $flagValue = $value['specs_value_ids'];
            }
        }
        $gids = array_column($skus, 'id', 'specs_value_ids');
        if ($goods['goods_specs_type'] == 1){  //单规格
            $sku = [];
        }else{   //多规格
            //获取sku
            $sku = (new SpecsValue())->dealGoodsSkus($gids,$flagValue);
        }


        $result = [
            'title' => $goods['title'],
            'price' => $goodsSku['price'],
            'cost_price' => $goodsSku['cost_price'],
            'stock' => $goodsSku['stock'],
            'gids' => $gids,
            'image' => $goods['carousel_image'],
            'sku' => $sku,
            'detail' => [
                'd1' => [
                    '商品编码' => $goodsSku['id'],
                    '上架时间' => $goods['create_time']
                ],
                'd2' => preg_replace('/(<img.+?src=")(.*?)/',
                    '$1' . request()->domain() . '$2',
                    $goods['description'])
            ]
        ];
        //详情页pv统计,redis计数器统计
        Cache::inc('mall_detail_pv_'.$goods['id']);

        return $result;
    }


}