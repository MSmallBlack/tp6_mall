<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/17
 * time   :22:32
 */
namespace app\api\controller;


use app\BaseController;
use think\exception\HttpResponseException;


/**
 * 非登录时继承
 */
class ApiBase extends BaseController
{
    public $accessToken = "";
    public $userId = 0;
    public $username = "";

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub


    }


    /**
     * 处理initialize方法中的返回值
     * @param mixed ...$args
     */
    public function show(...$args)
    {
        throw new HttpResponseException(show(...$args));
    }


}