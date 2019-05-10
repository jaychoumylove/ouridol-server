<?php
namespace app\base\service;

class WxGzh
{


    public function getMsg()
    {
        $wxapi = new WxAPI('gzh');
        $wxapi->getAccessToken();

        require_once APP_PATH . 'wx/crypto/WXBizMsgCrypt.php';
        $pc = new \WXBizMsgCrypt($wxapi->appinfo['access_token'], $wxapi->appinfo['encoding_aes_key'], $wxapi->appinfo['appid']);
        $encryptMsg = '';
        $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
    }
}
