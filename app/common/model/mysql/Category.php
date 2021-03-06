<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/20
 * time   :0:23
 */

namespace app\common\model\mysql;


use think\Model;

class Category extends BaseModel
{


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
     * 获取子分类数目
     * @param $data
     * @return mixed
     */
    public function getChildrenInPids($data)
    {
        $where[] = ['pid', 'in', $data['pid']];
        $where[] = ['status', '<>', config('status.mysql.table_delete')];
        return $this->field('pid,count(*) as count')
            ->where($where)
            ->group('pid')
            ->select();
    }


    /**
     * 获取一级分类内容
     * @param int $pid
     * @param $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalByPid($pid = 0, $field)
    {
        $where = [
            'pid' => $pid,
            'status' => config('status.mysql.table_normal')
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return $this->field($field)->where($where)->order($order)->select();
    }

    /**
     * 获取id获取一级分类
     * @param $id
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFirstLevelCategoryById($id)
    {
        $field = 'id as category_id,name,icon';
        return $this->field($field)->where('id', $id)->select();
    }

    /**
     * 根据pid获取二级分类
     * @param $id
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSecondLevelCategoryById($id)
    {
        $field = 'id as category_id,name';
        return $this->field($field)->where('pid', $id)->select();
    }


    /**
     * 获取分类数据
     * @param $id
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategoryById($id)
    {
        $field = "id,name,pid";
        $where = [
            'id' => $id,
            'status' => config('status.mysql.table_normal')
        ];
        return $this->field($field)->where($where)->find();
    }

    /**
     * @param $pid
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategoryByPid($pid)
    {
        $field = "id,name";
        if(is_array($pid)){
            $where[] = ['pid','in',$pid];
            $where[] = ['status','=',config('status.mysql.table_normal')];
        }else{
            $where = [
                'pid' => $pid,
                'status' => config('status.mysql.table_normal')
            ];
        }

        $data = $this->field($field)->where($where)->select();
        return $data->toArray();
    }


}