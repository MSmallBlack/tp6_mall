<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :1:29
 */

namespace app\admin\controller;

use app\common\business\SpecsValue as SpecsValueBusiness;
use app\common\lib\Show;

/**
 * 规格属性
 */
class SpecsValue extends AdminBase
{
    /**
     * insert 属性值
     * @return \think\response\Json
     */
    public function save()
    {
        $specsId = input('param.specs_id', 0, 'intval');
        $name = input('param.name', '', 'trim');
        $data = [
            'specs_id' => $specsId,
            'name' => $name
        ];
        $id = (new SpecsValueBusiness())->add($data);
        if (!$id) {
//            return show(config('status.error'), '新增失败');
            return Show::error([], '新增失败');
        }
//        return show(config('status.success', 'ok', ['id' => $id]));
        return Show::success(['id' => $id]);
    }

    /**
     * 获取规格属性
     * @return \think\response\Json
     */
    public function getBySpecsId()
    {
        $specsId = input('param.specs_id', 0, 'intval');
        if (!$specsId) {
//            return show(config('status.success'), '没有数据');
            return Show::error([], '没有数据');
        }
        $res = (new SpecsValueBusiness())->getBySpecsId($specsId);
//        return show(config('status.success'), 'ok', $res);
        return Show::success($res);
    }
}