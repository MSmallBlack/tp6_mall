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
        'code' => 'require|number|min:4',
        'type' => [
            'require',
            'in' => '1,2'
        ]
    ];

    protected $message = [
        'username' => '用户名不能为空',
        'phone_number' => '电话号码不能为空',
        'code.require' => '短信验证码不能为空',
        'code.number' => '短信验证码必须为数字',
        'code.min' => '短信验证码长度不低于4',
        'type.require' => '类型必须',
        'type.in' => '类型数值错误'
    ];

    protected $scene = [
        'send_code' => ['phone_number'],
        'login' => [
            'phone_number', 'code', 'type'
        ]
    ];
}