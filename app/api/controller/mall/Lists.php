<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/31
 * time   :23:31
 */

namespace app\api\controller\mall;


use app\api\controller\ApiBase;
use app\common\business\Goods;
use app\common\lib\Show;

class Lists extends ApiBase
{

    /**
     * 商品列表
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        $pageSize = input('param.page_size', 10, 'intval');
        $categoryId = input('param.category_id', 0, 'intval');
        if (!$categoryId) {
            return Show::error();
        }
        $data = [
            'category_path_id' => $categoryId
        ];
        //点击排序
        $field = input('param.field', 'listorder', 'trim');
        $order = input('param.order', 2, 'intval');
        $order = $order == 2 ? 'desc' : 'asc';
        $order = [
            $field => $order
        ];

        $goods = (new Goods())->getNormalLists($data, $pageSize, $order);
        return Show::success($goods);
    }
}