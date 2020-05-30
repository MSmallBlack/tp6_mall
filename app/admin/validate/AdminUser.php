<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :0:23
 */

namespace app\admin\validate;


use think\Validate;

class AdminUser extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'captcha' => 'require|checkCaptcha'
    ];

    protected $message = [
        'username' => "用户名不能为空",
        'password' => "密码不能为空",
        'captcha' => "验证码不能为空",
    ];

    protected function checkCaptcha($value, $rule, $data = [])
    {
        if (!captcha_check($value)) {
            return "您输入的验证码不正确";
        }
        return true;
    }

}