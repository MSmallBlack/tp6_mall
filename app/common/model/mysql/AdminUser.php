<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/6
 * time   :23:58
 */
declare(strict_types=1);
namespace app\common\model\mysql;


use mysql_xdevapi\DatabaseObject;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;

class AdminUser extends Model
{
    /**
     * 根据用户名获取登录用户信息
     * @param string $username
     * @return DatabaseObject
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getAdminUserByUsername(string $username) : DatabaseObject
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
    public function updateAdminUserById(int $id,array $data) :bool
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