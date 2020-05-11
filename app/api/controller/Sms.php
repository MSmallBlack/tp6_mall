<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :23:56
 */
declare(strict_types=1);
namespace app\api\controller;


use app\api\validate\User;
use app\BaseController;
use think\exception\ValidateException;
use app\common\business\Sms as SmsBusiness;

class Sms extends BaseController
{
    /**
     * 发送短信验证码
     * @return object
     */
    public function code():object
    {
        $phoneNumber = input('param.phone_number','','trim');
        $data = [
            'phone_number' => $phoneNumber
        ];
        try {
            validate(User::class)->scene('send_code')->check($data);
        }catch (ValidateException $e){
            return show(config('status.error'),$e->getError());
        }
        //business调用
        if(SmsBusiness::sendCode($phoneNumber,6,'baidu')){
            return show(config('status.success'),'发送验证码成功');
        }
        return show(config('status.error'),'发送验证码失败');
    }
}