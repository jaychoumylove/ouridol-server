<?php
namespace app\base\service;

class WxMsg
{
    public function __construct($type = 'miniapp')
    {
        $this->appinfo = Appinfo::get(['type' => $type]);
    }

    public function getMsg()
    {
        $wxapi = new WxAPI('gzh');
        $wxapi->getAccessToken();

        require_once APP_PATH . 'wx/crypto/WXBizMsgCrypt.php';
        $pc = new \WXBizMsgCrypt($wxapi->appinfo['access_token'], $wxapi->appinfo['encoding_aes_key'], $wxapi->appinfo['appid']);
        $encryptMsg = '';
        $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
    }

    /**
     * 验证消息signature
     * @param string $token signature_token
     */
    public function checkSignature()
    {
        $signature = input('signature');
        $timestamp = input('timestamp');
        $nonce = input('nonce');
        $echostr = input('echostr');

        $token = $this->appinfo['signature_token'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            // 验证通过
            if ($echostr) die($echostr);
        } else {
            die();
        }
    }
}
