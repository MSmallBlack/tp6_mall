<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/27
 * time   :22:50
 */

namespace app\admin\controller;


use app\common\lib\Show;
use think\facade\Filesystem;


/**
 * 图片上传
 */
class Image extends AdminBase
{

    /**
     * 图片上传
     * @return \think\response\Json
     */
    public function upload()
    {
        if (!$this->request->isPost()) {
            return Show::error([], '请求不合法');
        }
        $file = $this->request->file('file');
        //写入文件
//        $fileName = Filesystem::putFile('upload',$file);  写入到runtime目录
        //写入到public目录下
        $fileName = Filesystem::disk('public')->putFile('image', $file);
        if (!$fileName) {
            return Show::error([], '上传失败');
        }
        $imgUrl = '/upload/' . $fileName;
        return Show::success(['image' => $imgUrl], '上传成功');
    }


    /**
     * lay图片上传
     * @return \think\response\Json
     */
    public function layUpload()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求不合法');
        }
        $file = $this->request->file('file');
        $fileName = Filesystem::disk('public')->putFile('image', $file);
        $res = [
            'code' => 1,
            'data' => []
        ];
        //失败
        if (!$fileName) {
            return json($res, 200);
        }
        //成功
        $imgUrl = '/upload/' . $fileName;
        $result = [
            'code' => 0,
            'data' => [
                'src' => $imgUrl
            ]
        ];

        return json($result, 200);

    }
}