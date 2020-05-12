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
    public $userObj = null;

    public function __construct()
    {
        $this->userObj = new UserModel();
    }

    public function login($data)
    {
        //----------------redis+token => login
        $redisCode = cache(config('redis.code_pre'), $data['phone_number']);
        //判断验证码是否和redis中的一致
        if (empty($redisCode) || $redisCode != $data['code']) {
            throw new Exception('验证码不存在');
        }
        $user = $this->userObj->getUserByPhoneNumber($data['phone_number']);
        if (!$user) {
            //insert
            $userData = [
                'username' => 'shop_name' . $data['phone_number'],
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config('status.mysql.table_normal')
            ];
            try {
                $this->userObj->save($userData);
                $userId = $this->userObj->id;
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
                $this->userObj->updateUserById($user->id, $userData);
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
        $res = cache(config_path('redis.token_pre') . $token, $redisData,Time::userLoginExpiresTime($data['type']));

        if ($res) {
            return [
                'token' => $token,
                'username' => $user->username
            ];
        } else {
            return false;
        }

    }
}