<?php
/**
 * Created by PhpStorm
 * author  :gogochen
 * date    :2020/3/1
 * time    :0:05
 */

namespace app\controller;


use app\BaseController;

class Demo extends BaseController
{
    public function show()
    {
        $array = [
            'id' => 1,
            'name' => 'gogochen'
        ];
        return json($array, 201);
    }

    public function request()
    {
        //获取request
//        dump($this->request->get());
        dump($this->request->param('abc',1,'intval'));
    }


}