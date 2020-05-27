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
            $res = $this->model->getNormalBySpecsId($specsId,'id,name');
        }catch (Exception $e){
            return [];
        }
        return $res->toArray();
    }
}