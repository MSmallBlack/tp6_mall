<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/5
 * time   :18:30
 */


namespace app\api\exception;


use think\Exception;
use think\exception\Handle;
use think\exception\HttpResponseException;
use think\Response;
use Throwable;

class Http extends Handle
{
    public $httpStatus = 500;

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof Exception) {
            return show($e->getCode(), $e->getMessage());
        }
        //处理apibase
        if ($e instanceof HttpResponseException) {
            return parent::render($request, $e);
        }
        if (method_exists($e, "getStatusCode")) {
            $httpStatus = $e->getStatusCode();
        } else {
            $httpStatus = $this->httpStatus;
        }
        // 添加自定义异常处理机制
        return show(config('status.error'), $e->getMessage(), [], $httpStatus);
    }
}