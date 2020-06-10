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


    /**
     *
     * @param $ids
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalInIds($ids)
    {
        return $this->whereIn('id',$ids)
            ->where('status',config('status.mysql.table_normal'))
            ->select();
    }
}