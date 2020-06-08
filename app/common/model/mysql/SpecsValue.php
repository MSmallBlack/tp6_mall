<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :1:37
 */

namespace app\common\model\mysql;



class SpecsValue extends BaseModel
{


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



    /**
     * 获取规格属性
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