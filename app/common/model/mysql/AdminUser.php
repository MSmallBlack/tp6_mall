<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :23:58
 */

namespace app\common\model\mysql;


use think\Model;

class AdminUser extends Model
{
    /**
     * 根据用户名获取登录用户信息
     * @param $username
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByUsername($username)
    {
        if(empty($username)){
            return false;
        }
        $where =[
            'username' => $username
        ];
        return $this->where($where)->find();
    }

    /**
     * 更新用户表信息
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateAdminUserById($id,$data)
    {
        if(empty(intval($id)) || empty($data) || !is_array($data)){
            return false;
        }
        $where = [
            'id' => $id
        ];
        return $this->where($where)->save($data);
    }
}