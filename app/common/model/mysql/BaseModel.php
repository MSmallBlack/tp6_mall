<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/28
 * time   :23:24
 */

namespace app\common\model\mysql;


use think\Model;

class BaseModel extends Model
{
    /**
     * 自动写入create_time时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;


    /**
     * update
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id,$data)
    {
        $data['update_time'] = time();
        return $this->where('id',$id)->save($data);
    }
}