<?php

namespace app\base\service;

use app\base\model\Appinfo;

/**服务端wx接口 */
class WxAPI
{
    public function __construct($id = 1)
    {
        $this->appinfo = Appinfo::get($id);
    }

    /**登录 */
    public function code2session($js_code)
    {

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code';
        $url = str_replace('APPID', $this->appinfo['appid'], $url);
        $url = str_replace('SECRET', $this->appinfo['appsecret'], $url);
        $url = str_replace('JSCODE', $js_code, $url);

        $data = false;
        return Common::request($url, $data);
    }

    /**统一下单API */
    public function unifiedorder($config)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $params = [
            'appid' => $this->appinfo['appid'],
            'mch_id' => $this->appinfo['paymchid'],
            'nonce_str' => Common::getRandCode(), // 随机字符串
            'body' => $config['body'], // 商品描述
            'out_trade_no' => $config['orderId'], // 商户订单号
            'total_fee' => $config['totalFee'] * 100, // 总金额 单位 分
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
            'notify_url' => $config['notifyUrl'],
            'trade_type' => $config['tradeType'],
        ];
        if ($config['tradeType'] == 'JSAPI') {
            // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识
            $params['openid'] = $config['openid'];
        }
        // 签名
        $params['sign'] = (new Payment())->makeSign($params);
        // 发送请求
        $xml = Common::toXml($params);
        $res = Common::request($url, $xml);
        return Common::fromXml($res);
    }
}
