<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/9
 * time   :0:05
 */

namespace app\api\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require',
        'phone_number' => 'require',
    ];

    protected $message = [
        'username' => '用户名不能为空',
        'phone_number' => '电话号码不能为空'
    ];

    protected $scene = [
        'send_code' => ['phone_number']
    ];
}