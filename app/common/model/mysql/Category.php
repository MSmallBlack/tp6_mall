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
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return $this->field($field)->where($where)->order($order)->select();
    }


    /**
     * 获取分类数据
     * @param $data
     * @param $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($data, $num = 10)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where('status', '<>', config('status.mysql.table_delete'))
            ->where($data)
            ->order($order)
            ->paginate($num);
        return $result;
    }


    /**
     * update
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        $data['update_time'] = time();
        return $this->where('id', $id)->save($data);
    }


    /**
     * 获取子分类数目
     * @param $data
     * @return mixed
     */
    public function getChildrenInPids($data)
    {
        $where[] = ['pid','in',$data['pid']];
        $where[] = ['status','<>',config('status.mysql.table_delete')];
        return $this->field('pid,count(*) as count')
            ->where($where)
            ->group('pid')
            ->select();
    }
}