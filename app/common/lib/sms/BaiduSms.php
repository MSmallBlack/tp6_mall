<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/9
 * time   :23:58
 */
declare(strict_types=1);

namespace app\common\lib\sms;


class BaiduSms implements SmsBase
{
    public static function sendCode(string $phone, int $code): bool
    {
        // TODO: Implement sendCode() method.
//        var_dump(1111);
        return true;
    }
}