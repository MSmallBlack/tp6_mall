<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/12
 * time   :22:39
 */

namespace app\api\controller;


use app\api\validate\User;
use app\BaseController;
use app\common\lib\Show;
use think\Exception;

class Login extends BaseController
{
    /**
     * 商城登录
     * @return object
     */
    public function index()
    {
        if (!$this->request->isPost()) {
            return show(config_path('status.error'), '非法请求');
        }
        //短信登录
        $phoneNumber = input('param.phone_number', '', 'trim');
        $code = input('param.code', 0, 'intval');
        //会话保存时长
        $type = input('param.type', 0, 'intval');
        //手机号，密码登录
        $password = input('param.password', '', 'trim');
        if (!empty($code)) {
            //参数校验
            $data = [
                'phone_number' => $phoneNumber,
                'code' => $code,
                'type' => $type
            ];
        } else {
            $data = [
                'phone_number' => $phoneNumber,
                'password' => $password,
                'type' => $type
            ];
        }
        $validate = new User();
        if (!$validate->scene('login')->check($data)) {
            return Show::error([], $validate->getError());
        }
        try {

            $result = (new \app\common\business\User())->login($data);

        } catch (Exception $e) {
            return Show::error([], $validate->getError(), $e->getCode());
        }
        if ($result) {
            return Show::success($result, '登录成功');
        }
        return Show::success($result, '登录成功');
    }
}