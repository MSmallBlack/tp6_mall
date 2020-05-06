<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :23:09
 */


namespace app\admin\controller;



use think\captcha\facade\Captcha;

/**
 * 验证码
 */
class Verify
{
    public function index()
    {
        return Captcha::create('verify');

    }
}