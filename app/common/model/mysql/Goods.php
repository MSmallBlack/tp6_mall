<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :23:46
 */

namespace app\common\model\mysql;


class Goods extends BaseModel
{

    /**
     * title search
     * @param $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        $query->where('title', 'like', '%' . $value . '%');
    }

    /**
     * create_time search
     * @param $query
     * @param $value
     */
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
    }

    /**
     * get goods paginate data
     * @param $likeKeys
     * @param $data
     * @param $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList($likeKeys, $data, $num)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        if (!empty($likeKeys)) {
            //搜索器
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        return $res->whereIn('status', [0, 1])->order($order)->paginate($num);
    }


    /**
     * 首页大图推荐
     * @param array $where
     * @param  $field
     * @param int $limit
     * @return \think\Collection
     */
    public function getNormalGoodsCondition($where, $field = true, $limit = 5)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $where['status'] = config('status.success');
        return $this->field($field)->where($where)->order($order)->limit($limit)->select();

    }

    /**
     * 图片获取
     * @param $value
     * @return string
     */
    public function getImageAttr($value)
    {
        return request()->domain() . $value;
    }

    public function getCarouselImageAttr($value)
    {
        if (!empty($value)) {
            $value = explode(',', $value);
            $res = array_map(function ($v) {
                return request()->domain() . $v;
            }, $value);
        }
        return $res;
    }


    /**
     * 获取分类商品数据
     * @param $categoryId
     * @param bool $field
     * @param int $limit
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalGoodsFindInSetCategoryId($categoryId, $field = true, $limit = 10)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        return $this->field($field)->whereFindInSet('category_path_id', $categoryId)
            ->where($where)->order($order)->limit($limit)->select();
    }


    /**
     * 根据商品分类获取数据
     * @param $data
     * @param int $pageSize
     * @param bool $field
     * @param array $order
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getNormalLists($data, $pageSize = 10, $field = true, $order)
    {
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        $res = $this;
        if (isset($data['category_path_id'])) {
            $res = $this->whereFindInSet('category_path_id', $data['category_path_id']);
        }
        return $res->field($field)->where($where)->order($order)->paginate($pageSize);
    }


    /**
     * 获取分类id
     * @param $categoryPathId
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategoryByCategoryPathId($categoryPathId)
    {
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        return $this->where($where)->whereFindInSet('category_path_id', $categoryPathId)->find();
    }

}