<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :23:58
 */

namespace app\common\business;

use AlibabaCloud\Client\Exception\ClientException;
use app\common\lib\ClassArr;
use app\common\lib\Num;
use app\common\lib\sms\AliSms;

class Sms
{

    /**
     * 发送短信验证码
     * @param string $phoneNumber
     * @param int $len
     * @param string $type
     * @return bool
     */
    public static function sendCode(string $phoneNumber, int $len, string $type = 'ali'): bool
    {
        $code = Num::getCode($len);

//        $sms = AliSms::sendCode($phoneNumber, $code);
        //工厂模式,找到对应的类库
        //首字母大写，类名
//        $type = ucfirst($type);
//        $class = "app\common\lib\sms\\".$type."Sms";
//        $sms = $class::sendCode($phoneNumber, $code);
        $classStats = ClassArr::smsClassStat();

        $classObj = ClassArr::initClass($type, $classStats);
        $sms = $classObj::sendCode($phoneNumber, $code);

        if ($sms) {
            //把code记录到redis
            cache(config('redis.code_pre') . $phoneNumber, $code, config('redis.code_expire'));
        }

        return $sms;
    }
}