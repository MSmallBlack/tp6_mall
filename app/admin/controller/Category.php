<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/19
 * time   :23:34
 */

namespace app\admin\controller;


use think\Exception;
use think\facade\View;
use app\common\business\Category as CategoryBusiness;
use think\response\Json;

class Category extends AdminBase
{
    public function index()
    {
        return View::fetch();
    }


    public function add()
    {
        try {
            $categorys = (new CategoryBusiness())->getNormalCategorys();
        }catch (Exception $e){
            $categorys = [];
        }
        return View::fetch('',[
            'categorys' => json_encode($categorys)
        ]);
    }


    /**
     * insert
     * @return Json
     */
    public function save()
    {
        $pid = input('param.pid',0,'intval');
        $name = input('param.name','','trim');
        $data = [
            'pid' => $pid,
            'name' => $name
        ];
        $validate = new \app\admin\validate\Category();
        if(!$validate->check($data)){
            return show(config('status.error'),$validate->getError());
        }
        try{
            (new CategoryBusiness())->add($data);
        }catch (Exception $e){
            return show(config('status.error'),$e->getMessage());
        }
        return show(config('status.success'),'ok');
    }
}