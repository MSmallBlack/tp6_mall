<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/19
 * time   :23:34
 */

namespace app\admin\controller;


use app\common\lib\ListPage;
use app\common\lib\Show;
use app\common\lib\Status;
use think\Exception;
use think\facade\View;
use app\common\business\Category as CategoryBusiness;
use think\response\Json;

class Category extends AdminBase
{
    public function index()
    {
        $pid = input('param.pid', 0, 'intval');
        $data = [
            'pid' => $pid
        ];
        try {
            $categorys = (new CategoryBusiness())->getLists($data, 5);
        } catch (Exception $e) {
            $categorys = ListPage::listIsEmpty(5);
        }

        return View::fetch('', [
            'categorys' => $categorys
        ]);
    }


    /**
     * 添加分类
     * @return string
     * @throws \Exception
     */
    public function add()
    {
        try {
            $categorys = (new CategoryBusiness())->getNormalCategorys();
        } catch (Exception $e) {
            $categorys = [];
        }
        return View::fetch('', [
            'categorys' => json_encode($categorys)
        ]);
    }


    /**
     * insert
     * @return Json
     */
    public function save()
    {
        $pid = input('param.pid', 0, 'intval');
        $name = input('param.name', '', 'trim');
        $data = [
            'pid' => $pid,
            'name' => $name
        ];
        $validate = new \app\admin\validate\Category();
        if (!$validate->check($data)) {
            return Show::error([], $validate->getError());
        }
        try {
            $res = (new CategoryBusiness())->add($data);
        } catch (Exception $e) {
            return Show::error([], $e->getMessage());
        }
        if ($res) {
            return Show::success();
        }
        return Show::error([], '新增分类失败');
    }


    /**
     * 排序
     * @return Json
     */
    public function listorder()
    {
        $id = input('param.id', 0, 'intval');
        $listorder = input('param.listorder', 0, 'intval');

        if (!$id) {
            return Show::error([], '参数错误');
        }
        try {
            $res = (new CategoryBusiness())->listorder($id, $listorder);
        } catch (Exception $e) {
            return Show::error([], $e->getMessage());
        }
        if (!$res) {
            return Show::error([], '排序失败');

        } else {
            return Show::success([], '排序成功');
        }
    }


    /**
     * update status
     * @return Json
     */
    public function status()
    {
        $status = input('param.status', 0, 'intval');
        $id = input('param.id', 0, 'intval');

        if (!$id || !in_array($status, Status::getTableStatus())) {
            return Show::error([], '参数错误');
        }

        try {
            $res = (new CategoryBusiness())->status($id, $status);
        } catch (Exception $e) {
            return Show::error([], $e->getMessage());
        }
        if ($res) {
            return Show::success([], '状态更新成功');
        } else {
            return Show::error([], '状态更新失败');
        }
    }


    /**
     * 弹出层分类显示
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function dialog()
    {
        //获取一级分类
        $categorys = (new CategoryBusiness())->getNormalByPid();
        return View::fetch('', [
            'categorys' => json_encode($categorys)
        ]);
    }


    public function getByPid()
    {
        $pid = input('param.pid', 0, 'intval');
        $categorys = (new CategoryBusiness())->getNormalByPid($pid);
        return show(config('status.success'), 'ok', $categorys);
    }


}