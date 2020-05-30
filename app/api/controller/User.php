<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/17
 * time   :22:36
 */

namespace app\api\controller;

use app\common\business\User as userBusiness;
use app\api\validate\User as userValidate;
use app\common\lib\Show;
use think\Exception;

class User extends AuthBase
{
    public function index()
    {
        $normalUser = (new UserBusiness())->getNormalUserById($this->userId);
        $result = [
            'id' => $this->userId,
            'username' => $normalUser['username'],
            'sex' => $normalUser['sex']
        ];
        return Show::success($result);

    }

    /**
     * 更新用户数据
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function update()
    {
        $username = input('param.username', '', 'trim');
        $sex = input('param.sex', 0, 'intval');
        $data = [
            'username' => $username,
            'sex' => $sex
        ];
        $validate = (new userValidate())->scene('update_user');
        if (!$validate->check($data)) {
            return Show::error([], $validate->getError());
        }
        try {
            $user = (new UserBusiness())->updateUser($this->userId, $data);
        } catch (Exception $e) {
            return Show::error([], $e->getMessage());
        }
        if (!$user) {
            return Show::error([], '更新失败');
        }
        return Show::success();
    }
}