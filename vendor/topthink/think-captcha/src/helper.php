<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

use think\captcha\facade\Captcha;
use think\facade\Route;
use think\Response;

/**
 * @param string $config
 * @return \think\Response
 */
function captcha($config = null): Response
{
    return Captcha::create($config);
}

/**
 * @param $config
 * @return string
 */
function captcha_src($config = null): string
{
    return Route::buildUrl('/captcha' . ($config ? "/{$config}" : ''));
}

/**
 * @param $id
 * @return string
 */
<<<<<<< HEAD
function captcha_img($id = ''): string
{
    $src = captcha_src($id);

    return "<img src='{$src}' alt='captcha' onclick='this.src=\"{$src}?\"+Math.random();' />";
=======
function captcha_img($id = '',$width = '',$height = ''): string
{
    $src = captcha_src($id);
    $style = '';
    if($width && $height){
        $style = "width=".$width." height=".$height;
    }
    return "<img src='{$src}' $style alt='captcha' onclick='this.src=\"{$src}?\"+Math.random();' />";
>>>>>>> b272d874792455ab9666a377e8a4ff09cd654bc4
}

/**
 * @param string $value
 * @return bool
 */
function captcha_check($value)
{
    return Captcha::check($value);
}
