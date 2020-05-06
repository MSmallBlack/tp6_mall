<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/5
 * time   :17:13
 */


namespace app\controller;


use app\BaseController;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;

class Data extends BaseController
{
    public function index()
    {
        //查询构造器
        $data = Db::table('mall_test')->where('id',1)->select();
        //容器
//        $data = app('db')->tabale('mall_test')->where('id',1)->find();
        var_dump($data);
    }
}