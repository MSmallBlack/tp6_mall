<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :1:14
 */

namespace app\common\business;


use app\common\model\mysql\Specs as SpecsModel;
use think\Exception;

class Specs
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SpecsModel();
    }

    /**
     * 获取规格
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalSpecs()
    {
        try {
            $res = $this->model->getNormalSpecs();
        }catch (Exception $e){
            return [];
        }
        return $res->toArray();
    }
}