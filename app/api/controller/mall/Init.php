<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/6/11
 * time   :1:07
 */

namespace app\api\controller\mall;


use app\api\controller\AuthBase;
use app\common\business\Cart;
use app\common\lib\Show;

class Init extends AuthBase
{


    /**
     * 获取购物车商品总数
     * @return \think\response\Json
     */
    public function index()
    {
        if(!$this->request->isPost()){
            return Show::error([],'请求不合法');
        }

        $count = (new Cart())->getCartCount($this->userId);
        $res = [
            'cart_num' => $count
        ];
        return Show::success($res);
    }
}