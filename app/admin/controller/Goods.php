<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :0:08
 */

namespace app\admin\controller;

use think\Exception;
use think\exception\ValidateException;
use think\facade\Log;
use think\facade\View;
use app\common\business\Goods as GoodsBusiness;


/**
 * 商品
 */
class Goods extends AdminBase
{
    /**
     * goods list
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        $data = [];
        $title = input('param.title','','trim');
        $time = input('param.time','','trim');
        if(!empty($title)){
            $data['title'] = $title;
        }
        if(!empty($time)){
            $data['create_time'] = explode(' _ ',$time);
        }
        $goods = (new GoodsBusiness())->getList($data,5);
        return View::fetch('',[
            'goods' => $goods
        ]);
    }


    public function add()
    {
        return View::fetch();
    }

    /**
     * insert goods
     * @return \think\response\Json
     * @throws Exception
     */
    public function save()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '参数不合法');
        }
        //接受参数
        $data = input('param.');
        //实际应运发现，时间间隔太短就会失效，极其不友好，故取消验证
        //表单令牌
//        $check = $this->request->checkToken('__token__');
//        if($check === false){
//            //记录日志
//            Log::create("token验证失败,非法请求");
////            throw new ValidateException('token验证失败');
//            return show(config('status.error'), 'token验证失败，非法请求');
//        }
        $data['category_path_id'] = $data['category_id'];
        $result = explode(',', $data['category_path_id']);
        $data['category_id'] = end($result);

        $res = (new GoodsBusiness())->insertData($data);

        if (!$res) {
            return show(config('status.error'), '新增商品失败');
        }
        return show(config('status.success'), '新增商品成功');


    }
}