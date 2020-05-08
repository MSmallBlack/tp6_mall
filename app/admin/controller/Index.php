<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :23:29
 */

namespace app\admin\controller;


use app\BaseController;
use think\facade\View;

class Index extends AdminBase
{
    public function index()
    {
        return View::fetch();
    }

    public function welcome()
    {
        return View::fetch();
    }

}