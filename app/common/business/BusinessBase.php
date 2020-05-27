<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :22:34
 */

namespace app\common\business;


use think\Exception;

class BusinessBase
{

    /**
     * insert
     * @param $data
     * @return int
     */
    public function add($data)
    {
        $data['status'] = config('status.mysql.table_normal');

        try {
            $this->model->save($data);
        } catch (Exception $e) {
            return 0;
        }
        return $this->model->id;
    }
}