<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :0:08
 */

namespace app\admin\controller;




use think\facade\View;

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
}