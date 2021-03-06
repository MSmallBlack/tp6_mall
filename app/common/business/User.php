<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/12
 * time   :22:51
 */

namespace app\common\business;

use app\common\lib\Src;
use app\common\lib\Time;
use app\common\model\mysql\User as UserModel;
use think\Exception;

/**
 * 商城用户
 */
class User
{
    public $model = null;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * 登录
     * @param $data
     * @return array|bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login($data)
    {
        //----------------redis+token => login

        $user = $this->model->getUserByPhoneNumber($data['phone_number']);
        if (!empty($data['code'])) {    //手机验证码登录
//            $user = $this->model->getUserByPhoneNumber($data['phone_number']);
            $redisCode = cache(config('redis.code_pre'), $data['phone_number']);
            //判断验证码是否和redis中的一致
            if (empty($redisCode) || $redisCode != $data['code']) {
                throw new Exception('验证码不存在');
            }
        } else {   //手机号，密码登录
            //判断密码是否正确
            if ($user->password != md5(config('status.md5_str') . $data['password'])) {
                throw new Exception('密码错误');
            }
        }
        if (!$user) {
            //insert
            $userData = [
                'username' => 'shop_name' . $data['phone_number'],
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config('status.mysql.table_normal')
            ];
            try {
                $this->model->save($userData);
                $userId = $this->model->id;
            } catch (Exception $e) {
                throw new Exception('数据库内部异常');
            }
        } else {
            //update
            $userData = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip()
            ];
            try {
                $this->model->updateUserById($user->id, $userData);
            } catch (Exception $e) {
                throw new Exception('数据库内部异常');
            }
        }
        //生成token
        $token = Src::getLoginToken($data['phone_number']);

        $redisData = [
            'id' => $user->id,
            'username' => $user->username,
        ];
        //redis记录token
        $res = cache(config('redis.token_pre') . $token, $redisData, Time::userLoginExpiresTime($data['type']));
        if ($res) {
            return [
                'token' => $token,
                'username' => $user->username
            ];
        } else {
            return false;
        }

    }


    /**
     * 通过id获取正常的user
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalUserById($id)
    {
        $user = $this->model->getUserById($id);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }


    /**
     * 通过用户名获取正常的user
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalUserByUsername($username)
    {
        $user = $this->model->getUserByUsername($username);
        if (!$user || $user->status != config('status.mysql.table_normal')) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * 更新USER数据
     * @param $id
     * @param $data
     * @throws Exception
     */
    public function updateUser($id, $data)
    {
        $normalUser = $this->getNormalUserById($id);
        if (!$normalUser) {
            throw new Exception('不存在该用户');
        }
        //检查用户名是否存在
        $user = $this->getNormalUserByUsername($data['username']);
        if ($user && $user['id'] != $id) {
            throw new Exception('该用户已经存在，请重新设置');
        }
        try {
            return $this->model->updateUserById($id, $data);
        } catch (Exception $e) {
            throw new Exception('数据库内部异常');
        }
    }

}