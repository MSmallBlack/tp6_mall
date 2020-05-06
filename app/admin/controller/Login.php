<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :22:26
 */


namespace app\admin\controller;


use app\BaseController;
use app\common\model\mysql\AdminUser;
use think\facade\Log;
use think\facade\View;

class Login extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function md5()
    {
        //admin   123456
//        echo md5('admin_user'.'123456');
        halt(session(config('admin.session_admin')));
    }

    public function check()
    {
        if(!$this->request->isPost()){
            return show(config('status.error'),'请求方式错误');
        }
        try {
            $username = $this->request->param('username','','trim');
            $password = $this->request->param('password','','trim');
            $captcha = $this->request->param('captcha','','trim');
            if(empty($username) || empty($password) || empty($captcha)){
                return show(config('status.error'),'参数不能为空');
            }
            //验证码，存进session，要开启session，在中间件中开启
            if(!captcha_check($captcha)){
                //验证码错误
                return show(config('status.error'),'验证码不正确');
            }
            $adminUserObj = new AdminUser();
            $adminUser = $adminUserObj->getAdminUserByUsername($username);
            if(empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')){
                return show(config('status.error'),'不存在该用户');
            }
            if ($adminUser->password != md5(config('status.md5_str').$password)){
                return show(config('status.error'),'密码错误');
            }
            //更新数据表
            $updateData = [
                'update_time' => time(),
                'last_login_time' => time(),
                'last_login_ip' => $this->request->ip()
            ];
            $result = $adminUserObj->updateAdminUserById($adminUser->id,$updateData);
            if (!$result){
                return show(config('status.error'),'登录失败');
            }
        }catch (\Exception $e){
            //记录日志
            Log::info($e->getMessage());
            return show(config('status.error'),'内部异常,登录失败');
        }
        //记录session
        session(config('admin.session_admin'),$adminUser->toArray());
        return show(config('status.success'),'登录成功');
    }
}