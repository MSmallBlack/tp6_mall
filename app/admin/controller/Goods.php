<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :0:08
 */

namespace app\admin\controller;

use think\Exception;
use think\facade\View;
use app\common\business\Goods as GoodsBusiness;


/**
 * 商品
 */
class Goods extends AdminBase
{
    public function index()
    {
        return View::fetch();
    }


    public function add()
    {
        return View::fetch();
    }

    public function save()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '参数不合法');
        }
        //接受参数
        $data = input('param.');
        $data['category_path_id'] = $data['category_id'];
        $result = explode(',', $data['category_path_id']);
        $data['category_id'] = end($result);

        try {
            $res = (new GoodsBusiness())->insertData($data);
        } catch (Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if (!$res) {
            return show(config('status.error'), '新增商品失败');
        }
        return show(config('status.success'), '新增商品成功');


    }
}