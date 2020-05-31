<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :23:54
 */

use think\facade\Route;

//发送验证码
Route::rule('smscode','Sms/code','POST');

//商城登录接口
Route::rule('login','Login/index','POST');

//资源路由
Route::resource('user','User');

//商品列表
Route::rule('lists','mall.lists/index');


Route::rule('subcategory/:id','category/sub');