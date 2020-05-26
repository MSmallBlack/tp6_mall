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

class SpecsValue
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SpecsValueModel();
    }


    /**
     * insert
     * @param $data
     * @return bool|mixed
     */
    public function add($data)
    {
        $data['status'] = 1;

        try {
            $this->model->save($data);
        }catch (Exception $e){
            return 0;
        }
        return $this->model->id;
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