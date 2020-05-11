<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/10
 * time   :0:10
 */

namespace app\common\lib;


class ClassArr
{
    public static function smsClassStat()
    {
        return [
            'ali' => "app\common\lib\sms\AliSms",
            'baidu' => "app\common\lib\sms\BaiduSms",
        ];
    }
    public static function initClass(string $type,array $class,array $params = [],bool $needInstance = false)
    {
        //调用的方法是静态的，返回类库就可以
        //不是静态的，就要返回对象
        if (!array_key_exists($type,$class)){
            return false;
        }
        $className = $class[$type];
//        new \ReflectionClass('A')  => 建立A反射类
//        $needInstanceArgs($args) => 实例化A对象
        return $needInstance == true ? (new \ReflectionClass($className)) ->newInstanceArgs($params) : $className;
    }
}