<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/20
 * time   :0:23
 */

namespace app\common\model\mysql;


use think\Model;

class Category extends Model
{
    /**
     * 自动写入create_time时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;


    /**
     * 通过name获取category
     * @param $name
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategoryByName($name)
    {
        $where = [
            'name' => $name
        ];
        return $this->where($where)->find();
    }

    /**
     * 获取状态正常的分类
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategorys($field = "*")
    {
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        return $this->field($field)->where($where)->select();
    }
}