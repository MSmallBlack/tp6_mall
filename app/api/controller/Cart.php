<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/9
 * time   :23:33
 */

namespace app\api\controller;


use app\common\lib\Show;
use think\facade\Cache;
use app\common\business\Cart as CartBusiness;

/**
 * 购物车
 */
class Cart extends AuthBase
{

    /**
     * 加入购物车
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add()
    {
        if(!$this->request->isPost()){
            return Show::error();
        }
        $id = input('param.id',0,'intval');
        $num = input('param.num',0,'intval');
        if(!$id || !$num){
            Login::error('参数不合法');
            return Show::error([],'参数不合法');
        }
        $res = (new CartBusiness())->insertRedis($this->userId,$id,$num);
        if(!$res === FALSE){
            Login::error('redis insert error');
            return Show::error();
        }
        return Show::success();

    }
}