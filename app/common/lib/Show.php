<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/30
 * time   :23:50
 */

namespace app\common\lib;


class Show
{
    /**
     * api success format
     * @param array $data
     * @param string $message
     * @return \think\response\Json
     */
    public static function success($data = [],$message = 'ok')
    {
        $result = [
            'status' => config('status.success'),
            'message' => $message,
            'result' => $data
        ];
        return json($result);
    }

    /**
     * api error format
     * @param array $data
     * @param string $message
     * @param  $status
     * @return \think\response\Json
     */
    public static function error($data = [],$message = 'error',$status = 0)
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'result' => $data
        ];
        return json($result);
    }
}