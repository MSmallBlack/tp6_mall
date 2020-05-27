<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :0:12
 */

namespace app\admin\controller;


use think\facade\View;
use app\common\business\Specs as SpecsBusiness;


/**
 * 规格
 */
class Specs extends AdminBase
{

    /**
     * 获取规格
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function dialog()
    {
        $specs = (new SpecsBusiness())->getNormalSpecs();
        return View::fetch('',[
            'specs' => json_encode($specs)
        ]);
    }
}