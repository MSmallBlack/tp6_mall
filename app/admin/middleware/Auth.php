<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/7
 * time   :23:27
 */
declare(strict_types=1);

namespace app\admin\middleware;


class Auth
{
    public function handle($request, \Closure $next)
    {
        //前置中间件,$request不能拿到controller
        //后置中间件可以拿到controller
        //根据pathinfo判断
        //前置中间件
//        if (empty(session(config('admin.session_admin')) && preg_match('/login/',$request->pathinfo()))) {
//            return redirect((string)url('login/index'));
//        }
        return $next($request);
    }

}