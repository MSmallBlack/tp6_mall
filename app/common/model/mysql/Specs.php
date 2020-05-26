<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :1:10
 */

namespace app\common\model\mysql;


use think\Model;

class Specs extends Model
{

    /**
     * 自动写入create_time时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;

    /**
     * 获取状态正常的规格名称
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalSpecs()
    {
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        return $this->field('id,name')->where($where)->select();
    }
}