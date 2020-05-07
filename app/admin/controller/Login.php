<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :22:26
 */


namespace app\admin\controller;

use Exception;
use think\facade\Log;
use think\facade\View;

class Login extends AdminBase
{
    public function initialize()
    {
        if ($this->isLogin()) {
            return $this->redirect(url('index/index'));
        }
    }

    public function index()
    {
        return View::fetch();
    }

    /**
     * 登录
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function check()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求方式错误');
        }

        $username = $this->request->param('username', '', 'trim');
        $password = $this->request->param('password', '', 'trim');
        $captcha = $this->request->param('captcha', '', 'trim');
        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha
        ];
        $validate = new \app\admin\validate\AdminUser();
        //验证
        if (!$validate->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        try {
            $adminUserBusiness = new \app\admin\business\AdminUser();
            $result = $adminUserBusiness->login($data);
        }catch (Exception $e){
            Log::error($e->getMessage());
            return show(config('status.error'), $e->getMessage());
        }

        if($result){
            return show(config('status.success'), "登录成功");
        }
        return show(config('status.error'),$validate->getError());
    }
}