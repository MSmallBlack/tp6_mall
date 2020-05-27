<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :1:37
 */

namespace app\common\model\mysql;


use think\Model;

class SpecsValue extends Model
{
    /**
     * 自动写入create_time时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;


    /**
     * 获取同一规格下的属性
     * @param $specsId
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalBySpecsId($specsId,$field = "*")
    {
        $where = [
            'specs_id' => $specsId,
            'status' => config('status.mysql.table_normal')
        ];
        return $this->field($field)->where($where)->select();
    }
}