<?php
namespace app\base\service;

class WxPay
{
    public function __construct($w = null)
    {
        $this->appinfo = Common::getAppinfo($w);
    }
    
    public function returnFont($res)
    {
        if ($res['result_code'] == 'SUCCESS') {
            if ($res['trade_type'] == 'JSAPI') {
                // JSAPI支付
                $returnData = [
                    'appId' => $this->appinfo['appid'],
                    'timeStamp' => (string)time(),
                    'nonceStr' => $res['nonce_str'],
                    'package' => 'prepay_id=' . $res['prepay_id'],
                    'signType' => 'MD5',
                ];
                $returnData['paySign'] = $this->makeSign($returnData);
            }

            Common::res(['data' => $returnData]);
        } else {
            // 支付异常
            Common::res(['code' => 600, 'data' => $res]);
        }
    }

    /**获取签名 */
    public function makeSign($params)
    {
        ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
        foreach ($params as $key => $item) {
            if (!empty($item)) {         //剔除参数值为空的参数
                $newArr[] = $key . '=' . $item;     // 整合新的参数数组
            }
        }
        $stringA = implode("&", $newArr);         //使用 & 符号连接参数
        $payapikey = $this->appinfo['payapikey'];
        $stringSignTemp = $stringA . "&key=" . $payapikey;        //拼接key
        // key是在商户平台API安全里自己设置的
        $stringSignTemp = MD5($stringSignTemp);       //将字符串进行MD5加密
        $sign = strtoupper($stringSignTemp);      //将所有字符转换为大写
        return $sign;
    }

    public function notifyHandle()
    {
        //接收微信返回的数据数据,返回的xml格式
        $xmlData = file_get_contents('php://input');
        $data = Common::fromXml($xmlData);
        $sign = $data['sign'];
        unset($data['sign']); // 剔除sign再校验
        if ($sign == $this->makeSign($data) && $data['result_code'] == 'SUCCESS') {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $data;
        } else {
            die();
        }
    }
}
