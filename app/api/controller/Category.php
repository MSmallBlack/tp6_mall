<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/2
 * time   :1:45
 */

namespace app\api\controller;

use app\common\business\Category as CategoryBusiness;
use app\common\lib\Arr;
use app\common\lib\Show;
use think\Exception;
use think\facade\Log;
use think\response\Json;

/**
 * 商品分类
 */
class Category extends ApiBase
{

    /**
     * 商品分类
     * @return Json
     */
    public function index()
    {
        try {
            //获取所有分类
            $categoryBusiness = new CategoryBusiness();
            $categorys = $categoryBusiness->getNormalAllCategorys();

        } catch (Exception $e) {
            //记录日志
            (new Log)->record('内部异常', 'error');
            //返回空数据
            return Show::success([], '内部异常');
        }
        if (!$categorys) {
            //记录日志
            (new Log)->record('分类为空', 'error');
            return Show::success([], '数据为空');
        }
        $result = Arr::getTree($categorys);
        return Show::success($result);
    }

    /**
     * 点击分类搜索对应商品
     * @param $id
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function search($id)
    {

        $res = (new CategoryBusiness())->getEveryLevelCategory($id);
        if (empty($res)) {
            return Show::error([], 'error');
        }
        return Show::success($res);
    }


    /**
     * 下一级分类
     * @param $id
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sub($id)
    {
        $res = (new CategoryBusiness())->getCategoryByPid($id);
        if (empty($res)) {
            return Show::error([], 'error');
        }
        return Show::success($res);
    }
}