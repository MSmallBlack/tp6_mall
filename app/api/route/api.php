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

//商品详情
Route::rule('detail/:id','mall.detail/index');

//点击分类获取对应商品
Route::rule('category/search/:id','category/search');

//获取下一级商品分类
Route::rule('subcategory/:id','category/sub');

//购物车添加
Route::rule('cart/add','cart/add');

//购物车列表
Route::rule('mall.init','mall.init/index');

//删除购物车
Route::rule('cart/delete','cart/delete');

//更新购物车
Route::rule('cart/update','cart/update');

//获取购物车商品总数
Route::rule('cart/update','cart/update');