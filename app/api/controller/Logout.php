<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/17
 * time   :23:56
 */

namespace app\api\controller;



/**
 * 用户退出登录
 */
class Logout extends AuthBase
{
    public function index()
    {
        //删除redis中的缓存与token
        $res = cache(config('redis.token_pre') . $this->accessToken,NULL );
        if($res){
            return show(config('status.success'),'退出登录成功');
        }
        return show(config('status.error'),'退出登录失败');

    }
}