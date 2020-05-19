<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/20
 * time   :0:14
 */

namespace app\admin\validate;


use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'pid' => 'require',
        'name' => 'require'
    ];

    protected $message = [
        'pid' => "pid不能为空",
        'name' => "分类名称不能为空"
    ];


}