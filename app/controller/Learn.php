<?php
/**
 * Created by PhpStorm
 * author  :gogochen
 * date    :2020/3/1
 * time    :23:48
 */

namespace app\controller;


use app\Request;

class Learn
{
    public function index(Request $request)
    {
        //获取request
//        dump($request->param('abc'));
//        dump(input('abc'));
//        dump(\request()->param('abc'));
        dump(think\facade\Request::param('abc'));
    }
}