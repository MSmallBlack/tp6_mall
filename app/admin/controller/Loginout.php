<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/7
 * time   :22:53
 */

namespace app\admin\controller;


class Loginout extends AdminBase
{
    public function index()
    {
        var_dump(111);
        //清除session
        session(config('admin.session_admin'),null);
        return redirect(url('login/index'));
    }
}