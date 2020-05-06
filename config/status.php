<?php
/**
 * Created by PhpStorm
 * author  :gogochen
 * date    :2020/3/2
 * time    :0:31
 */
/**
 * 该文件为业务状态码配置文件
 */

return [
    'success' => 1,
    'error' => 0,
    'not_login' => -1,
    'user_is _register' => -2,
    'action_not_found' => -3,
    'controller_not_found' => -4,
    //数据表状态
    'mysql' => [
        'table_normal' => 1,//正常
        'table_pending' => 0,//待审
        'table_delete' => 2 //删除
    ],
    //MD5加盐字符串
    'md5_str' => 'admin_user'
];