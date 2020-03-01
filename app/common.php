<?php
// 应用公共文件

/**
 * 通用化API输出格式
 * @param $status
 * @param string $message
 * @param array $data
 * @param int $httpStatusCode
 * @return think\response\Json
 */
function show($status, $message = 'error', $data = [], $httpStatusCode = 200)
{
    $result = [
        'status' => $status,
        'message' => $message,
        'result' => $data
    ];
    return json($result, $httpStatusCode);
}
