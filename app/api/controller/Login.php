<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/12
 * time   :22:39
 */
declare(strict_types=1);

namespace app\api\controller;


use app\api\validate\User;
use app\BaseController;
use think\Exception;

class Login extends BaseController
{
    /**
     * 商城登录
     * @return object
     */
    public function index(): object
    {
        if(!$this->request->isPost()){
            return show(config_path('status.error'),'非法请求');
        }
        $phoneNumber = input('param.phone_number','','trim');
        $code = input('param.code',0,'intval');
        $type = input('param.type',0,'intval');
        //参数校验
        $data = [
            'phone_number' => $phoneNumber,
            'code' => $code,
            'type' => $type
        ];
        $validate = new User();
        if(!$validate->scene('login')->check($data)){
            return show(config('status.error'),$validate->getError());
        }
        try {
            $result = (new \app\common\business\User())->login($data);

        }catch (Exception $e){
            return show($e->getCode(),$e->getMessage());
        }
        if($result){
            return show(config('status.success'),'登录成功');
        }
        return show(config('status.success'),'登录成功');
    }
}