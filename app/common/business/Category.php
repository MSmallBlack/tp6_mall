<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/20
 * time   :0:21
 */

namespace app\common\business;

use app\common\model\mysql\Category as CategoryModel;
use think\Exception;

/**
 * 分类管理
 */
class Category
{
    public $categoryObj = null;

    public function __construct()
    {
        $this->categoryObj = new CategoryModel();
    }

    /**
     * insert
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $data['status'] = config('status.mysql.table_normal');
        //查询分类名是否重复
        $catecory = $this->categoryObj->getCategoryByName($data['name']);
        if(!empty($catecory)){
            throw new Exception("分类名已存在，请重新设置分类名");
        }
        try {
            $this->categoryObj->save($data);
        } catch (Exception $e){
            throw new Exception("数据库内部异常");
        }

        //return主键id
        return $this->categoryObj->getLastInsID();
    }


    public function getNormalCategorys()
    {
        $field = "id,name,pid";
        $categorys = $this->categoryObj->getNormalCategorys($field);
        if(!$categorys){
            $categorys = [];
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }
}