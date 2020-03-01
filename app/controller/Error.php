<?php
/**
 * Created by PhpStorm
 * author  :gogochen
 * date    :2020/3/2
 * time    :0:13
 */

namespace app\controller;

/**
 * 控制器不存在时调用
 */
class Error
{
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $result = [
            'status' => config('status.controller_not_found'),
            'message'=> '找不到该控制器',
            'result' => null
        ];
        return json($result,400);
    }
}