<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :23:58
 */
declare(strict_types=1);
namespace app\common\business;

use AlibabaCloud\Client\Exception\ClientException;
use app\common\lib\Num;
use app\common\lib\sms\AliSms;

class Sms
{
    /**
     * 短信验证码
     * @param string $phoneNumber
     * @param int $len
     * @return bool
     * @throws ClientException
     */
    public static function sendCode(string $phoneNumber,int $len): bool
    {
        $code = Num::getCode($len);

        $sms = AliSms::sendCode($phoneNumber, $code);

        if($sms){
            //把code记录到redis
            cache(config('redis.code_pre').$phoneNumber,$code,config('redis.code_expire'));
        }

        return $sms;
    }
}