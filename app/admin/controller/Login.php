<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :22:26
 */


namespace app\admin\controller;

use app\admin\business\AdminUser;
use app\common\lib\Show;
use Exception;
use think\facade\Log;
use think\facade\View;
use think\response\Json;

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
     * @return Json
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
            return Show::error([], $validate->getError());
        }
        try {
            $adminUserBusiness = new AdminUser();
            $result = $adminUserBusiness->login($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Show::error([], $e->getMessage());
        }
        if ($result) {
            return Show::success([], '登录成功');
        }
        return Show::error([], $validate->getError());
    }
}