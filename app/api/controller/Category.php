<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/24
 * time   :0:47
 */

namespace app\api\controller;

use app\common\business\Category as CategoryBusiness;
use app\common\lib\Arr;

/**
 * 商品分类
 */
class Category extends ApiBase
{

    public function index()
    {
        //获取所有分类
        $categoryBusiness = new CategoryBusiness();
        $categorys = $categoryBusiness->getNormalAllCategorys();
        $result = Arr::getTree($categorys);
        return show(config('status.success'),'ok',$result);
    }
}