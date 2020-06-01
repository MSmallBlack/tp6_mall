<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/20
 * time   :0:21
 */

namespace app\common\business;

use app\common\model\mysql\Category as CategoryModel;
use think\Exception;
use app\common\model\mysql\Goods;

/**
 * 分类管理
 */
class Category
{
    public $model = null;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    /**
     * insert
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $data['status'] = config('status.mysql.table_normal');
        //查询分类名是否重复
        $catecory = $this->model->getCategoryByName($data['name']);
        if (!empty($catecory)) {
            throw new Exception("分类名已存在，请重新设置分类名");
        }
        try {
            $res = $this->model->save($data);
        } catch (Exception $e) {
            throw new Exception("数据库内部异常");
        }

        //return主键id
//        return $this->model->getLastInsID();
        return $res;
    }

    /**
     * 获取正常的分类
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategorys()
    {
        $field = "id,name,pid";
        $categorys = $this->model->getNormalCategorys($field);
        if (!$categorys) {
            $categorys = [];
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

    public function getNormalAllCategorys()
    {
        $field = "id as category_id,name,pid";
        $categorys = $this->model->getNormalCategorys($field);
        if (!$categorys) {
            $categorys = [];
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

    /**
     * 获取分类数据
     * @param $data
     * @param $num
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getLists($data, $num)
    {

        $list = $this->model->getLists($data, $num);
        if (!$list) {
            return [];
        }
        $res = $list->toArray();
        //分页
        $res['render'] = $list->render();

        //获取子分类
        //拿到pid
        $pids = array_column($res['data'], 'id');
        if (!empty($pids)) {
            $data = [
                'pid' => $pids
            ];
            //获取子分类数
            $idCountResult = $this->model->getChildrenInPids($data);
            $idCountResult = $idCountResult->toArray();
            $idCounts = [];

            foreach ($idCountResult as $countResult) {
                $idCounts[$countResult['pid']] = $countResult['count'];
            }
        }
        if ($res['data']) {
            foreach ($res['data'] as $key => $value) {
                $res['data'][$key]['childrenCount'] = $idCounts[$value['id']] ?? 0;
            }
        }
        return $res;

    }

    /**
     * get  数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getById($id)
    {
        $res = $this->model->find($id);
        if (empty($res)) {
            return [];
        }
        return $res->toArray();
    }


    /**
     * 排序
     * @param $id
     * @param $listorder
     * @return bool
     * @throws Exception
     */
    public function listorder($id, $listorder)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw new Exception("不存在该条记录");
        }
        $data = [
            'listorder' => $listorder
        ];
        try {
            $result = $this->model->updateById($id, $data);
        } catch (Exception $e) {
            return false;
        }

        return $result;
    }


    /**
     * 更新状态
     * @param $id
     * @param $status
     * @return bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function status($id, $status)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw new Exception("不存在该条数据");
        }
        if ($res['status'] == $status) {
            throw new Exception("状态修改前后一致");
        }
        $data = [
            'status' => $status
        ];
        try {
            $result = $this->model->updateById($id, $data);
        } catch (Exception $e) {
            return false;
        }
        return $result;
    }


    /**
     * 获取一级分类
     * @param int $pid
     * @param string $field
     * @return array
     */
    public function getNormalByPid($pid = 0, $field = 'id,name,pid')
    {
        try {
            $res = $this->model->getNormalByPid($pid, $field);
        } catch (Exception $e) {
            return [];
        }
        return $res->toArray();
    }

    /**
     * 获取一级分类下二级分类商品信息
     * @param $id
     * @return array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFirstAndSecondLevelCategoryById($id)
    {
        try {
            $res = $this->model->getFirstLevelCategoryById($id);
            if (!$res){
                return [];
            }
            foreach ($res as $key => $value){
                $res[$key]['list'] = $this->model->getSecondLevelCategoryById($value['category_id']);
            }
        }catch (Exception $e){
            return [];
        }
        return $res;
    }


    /**
     * 获取分类数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getEveryLevelCategory($id)
    {
        try {
            $res = (new Goods())->getCategoryByCategoryPathId($id);
            if(empty($res)){
                throw new Exception('数据为空');
            }
            $firstFocusId = $this->model->getCategoryById($id);
            $categoryArray = explode(',',$res['category_path_id']);
            $firstCategory = $this->model->getCategoryById($categoryArray[0]);
            $secondCategory = $this->model->getCategoryByPid($firstCategory['id']);
            $thirdCategory = $this->model->getCategoryByPid(array_column($secondCategory,'id'));
            $data = [
                'name' => $firstCategory['name'],
                'focus_ids' => [
                    $firstFocusId['pid'],
                    intval($id)
                ],
                'list' => [
                    $secondCategory,
                    $thirdCategory
                ]
            ];
        }catch (Exception $e){
            return [];
        }
        return $data;
    }


}