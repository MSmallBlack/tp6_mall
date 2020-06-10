<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :1:33
 */

namespace app\common\business;


use app\common\model\mysql\SpecsValue as SpecsValueModel;
use think\Exception;
use think\facade\Log;


/**
 * 规格属性
 */
class SpecsValue extends BusinessBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SpecsValueModel();
    }


    /**
     * 获取同一规格下的不同属性
     * @param $specsId
     * @return array
     */
    public function getBySpecsId($specsId)
    {
        try {
            $res = $this->model->getNormalBySpecsId($specsId, 'id,name');
        } catch (Exception $e) {
            return [];
        }
        return $res->toArray();
    }


    public function getNormalInIds($ids)
    {
        try {
            $specsValues = $this->model->getNormalInIds($ids);
        } catch (Exception $e) {
            return [];
        }
        $result = $specsValues->toArray();
        if (!$result) {
            return [];
        }
        //获取规格属性名称
        $specsNames = (new Specs())->getNormalSpecs();
        $specsNamesArray = array_column($specsNames, 'name', 'id');
        $res = [];
        foreach ($result as $key => $value) {
            $res[$value['id']] = [
                'name' => $value['name'],
                'specs_name' => $specsNamesArray[$value['specs_id']] ?? '',
            ];
        }
        return $res;

    }

    public function dealGoodsSkus($gids, $flagValue)
    {
        $specsValueKeys = array_keys($gids);
        foreach ($specsValueKeys as $specsValueKey) {
            $specsValueKey = explode(',', $specsValueKey);
            foreach ($specsValueKey as $key => $value) {
                //获取该规格下不同属性
                $new[$key][] = $value;
                //获取全部id
                $specsValueIds[] = $value;
            }
        }
        $specsValueIds = array_unique($specsValueIds);
        $flagValue = explode(',', $flagValue);
        $specsValues = $this->getNormalInIds($specsValueIds);
        $res = [];
        foreach ($new as $key => $value) {
            $value = array_unique($value);
            $list = [];
            foreach ($value as $v) {
                $list[] = [
                    'id' => $v,
                    'name' => $specsValues[$v]['name'],
                    'flag' => in_array($v, $flagValue) ? 1 : 0
                ];
            }
            $res[$key] = [
                'name' => $specsValues[$value[0]]['specs_name'],
                'list' => $list
            ];
        }
        return $res;

    }


    public function dealSpecsValue($skuIdSpecsValueIds)
    {
        $ids = array_values($skuIdSpecsValueIds);
        $ids = implode(',', $ids);
        $ids = array_unique(explode(',', $ids));
        $res = $this->getNormalInIds($ids);


        if (!$res) {
            return [];
        }
        $result = [];
        foreach ($skuIdSpecsValueIds as $skuId => $specs) {
            $specs = explode(',', $specs);
            $skuStr = [];
            foreach ($specs as $spec) {
                $skuStr[] = $res[$spec]['specs_name'] . ":" . $res[$spec]['name'];
            }
            //单引号 会报错，需注意
            $result[$skuId] = implode("  ", $skuStr);

        }
        return $result;
    }
}