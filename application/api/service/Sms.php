<?php
/*
 * 此文件用于验证短信服务API接口，供开发时参考
 * 执行验证前请确保文件为utf-8编码，并替换相应参数为您自己的信息，并取消相关调用的注释
 * 建议验证前先执行Test.php验证PHP环境
 *
 * 2017/11/30
 */
namespace app\api\service;
use app\base\service\SignatureHelper;
use app\base\service\Common;
use app\api\model\Cfg;

class Sms
{
    /**
     * 发送短信
     */
    public function send($phoneNumber) {

        $sms_code = Common::getRandNumber();
        $params = array ();

        // *** 需用户填写部分 ***
        // fixme 必填：是否启用https
        $security = true;

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $aliyun_access = Cfg::getCfg('aliyun_access');
        $accessKeyId = $aliyun_access['accessKeyId'];
        $accessKeySecret = $aliyun_access['accessKeySecret'];

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $phoneNumber;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "我们的偶像";

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
//        $params["TemplateCode"] = strpos($phoneNumber,'86')===false || strpos($phoneNumber,'86')== 0 ? "SMS_184821190" : 'SMS_184826126';//'SMS_184825931';
        $params["TemplateCode"] = strpos($phoneNumber,'1')==0 || strpos($phoneNumber,'86')== 0 ? "SMS_184821190" : 'SMS_184826126';


        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code" => $sms_code
        );

        // fixme 可选: 设置发送短信流水号
//         $params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
//         $params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-shanghai",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            )),
            $security
        );

        $content = json_decode(json_encode($content),true);
        $content['sms_code'] = $sms_code;
        $content['phoneNumber'] = $phoneNumber;
        $content['sms_time'] = time();

        return $content;
    }
}