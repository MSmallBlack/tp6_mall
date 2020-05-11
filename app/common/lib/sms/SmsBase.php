<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/9
 * time   :23:56
 */
declare(strict_types=1);
namespace app\common\lib\sms;


interface SmsBase{

    /**
     * @param string $phone
     * @param int $code
     * @return mixed
     */
    public static function sendCode(string $phone, int $code);

}