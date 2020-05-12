<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/12
 * time   :22:56
 */

namespace app\common\model\mysql;


use think\db\exception\DataNotFoundException;
use think\db\exception\DbException as DbExceptionAlias;
use think\db\exception\ModelNotFoundException;
use think\Model;

class User extends Model
{

    /**
     * 自动写入create_time时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;
    /**
     * 通过手机号获取用户信息
     * @param $phoneNumber
     * @return array|bool|Model|null
     * @throws DataNotFoundException
     * @throws DbExceptionAlias
     * @throws ModelNotFoundException
     */
    public function getUserByPhoneNumber($phoneNumber)
    {
        if(empty($phoneNumber)){
            return false;
        }
        $where =[
            'phone_number' => $phoneNumber
        ];
        return $this->where($where)->find();
    }

    /**
     * 更新用户信息
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateUserById($id,$data)
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