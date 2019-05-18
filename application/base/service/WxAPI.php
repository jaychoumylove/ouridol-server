<?php

namespace app\base\service;

use app\base\model\Appinfo;

/**服务端wx接口 */
class WxAPI
{
    public function __construct($w = null)
    {
        $this->appinfo = Common::getAppinfo($w);
    }

    /**
     * 登录
     */
    public function code2session($js_code)
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code';
        $url = str_replace('APPID', $this->appinfo['appid'], $url);
        $url = str_replace('SECRET', $this->appinfo['appsecret'], $url);
        $url = str_replace('JSCODE', $js_code, $url);

        return Common::request($url, false);
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
        $params['sign'] = (new WxPay())->makeSign($params);
        // 发送请求
        $xml = Common::toXml($params);
        $res = Common::request($url, $xml);
        return Common::fromXml($res);
    }

    /**
     * 检查更新accessToken
     * @return string access_token
     */
    public function getAccessToken()
    {
        if (strtotime($this->appinfo['access_token_expire']) - 300 < time()) {
            // 更新accessToken
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET';
            $url = str_replace('APPID', $this->appinfo['appid'], $url);
            $url = str_replace('APPSECRET', $this->appinfo['appsecret'], $url);

            $res = Common::request($url, false);
            if (isset($res['access_token'])) {
                Appinfo::where(['id' => $this->appinfo['id']])->update([
                    'access_token' => $res['access_token'],
                    'access_token_expire' => date('Y-m-d H:i:s', time() + $res['expires_in']),
                ]);
                return $res['access_token'];
            }
        } else {
            return $this->appinfo['access_token'];
        }
    }

    /**
     * 发送客服消息
     * customerServiceMessage.send
     * @param string $openid 用户openid
     * @param array $msgType
     * @param array $msgBody
     */
    public function sendCustomerMsg($openid, $msgType, $msgBody)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN';

        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;
        $url = str_replace('ACCESS_TOKEN', $accessToken, $url);

        $data = [
            'touser' => $openid,
            "msgtype" => $msgType,
            $msgType => $msgBody
        ];

        return Common::request($url, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 新增临时素材
     */
    public function uploadMedia($filePath)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE';
        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;
        $url = str_replace('ACCESS_TOKEN', $accessToken, $url);
        $url = str_replace('TYPE', 'image', $url);

        $data = ['media' => new \CURLFile($filePath, false, false)];
        return Common::request($url, $data);
    }

    /**
     * 获取临时素材
     * @param mixed $mediaId 媒体文件ID，即uploadVoice接口返回的serverID
     */
    public function getMedia($mediaId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID';

        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;
        $url = str_replace('ACCESS_TOKEN', $accessToken, $url);
        $url = str_replace('MEDIA_ID', $mediaId, $url);

        return Common::request($url, false);
    }
}
