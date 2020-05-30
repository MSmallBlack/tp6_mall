<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :23:33
 */

namespace app\common\lib\sms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\facade\Log;

class AliSms implements SmsBase
{
    /**
     * 阿里云短信发送
     * @param  $phone
     * @param  $code
     * @return bool
     * @throws ClientException
     */
    public static function sendCode($phone, $code)
    {
        if (empty($phone) || empty($code)) {
            return false;
        }
        AlibabaCloud::accessKeyClient(config('aliyun.access_key_id'), config('aliyun.access_key_secret'))
            ->regionId(config('aliyun.region_id'))
            ->asDefaultClient();
        $templateParam = [
            'code' => $code
        ];
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host(config('aliyun.host'))
                ->options([
                    'query' => [
                        'RegionId' => config('aliyun.region_id'),
                        'PhoneNumbers' => $phone,
                        'SignName' => config('aliyun.sign_name'),
                        'TemplateCode' => config('aliyun.template_code'),
                        'TemplateParam' => json_encode($templateParam)
                    ],
                ])
                ->request();
            //记录到日志
            Log::info('aliyun-sendCode-result' . $phone . '|' . json_encode($result->toArray()));
//            print_r($result->toArray());
        } catch (ClientException $e) {
            Log::error('aliyun-sendCode-ClientException' . $e->getMessage());
            return false;
//            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            Log::error('aliyun-sendCode-ServerException' . $e->getMessage());
            return false;
//            echo $e->getErrorMessage() . PHP_EOL;
        }
        if (isset($result['Code']) && $result['Code'] == 'OK') {
            return true;
        }
        return false;
    }
}