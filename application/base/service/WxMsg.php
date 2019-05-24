<?php
namespace app\base\service;

use app\api\model\WxImg;

class WxMsg
{
    public function __construct($w = null)
    {
        $this->appinfo = Common::getAppinfo($w);
    }

    /**
     * 解密并返回收到的消息体
     * @return array 消息体
     */
    public function getMsg()
    {
        require_once APP_PATH . 'wx/crypto/wxBizMsgCrypt.php';
        $pc = new \WXBizMsgCrypt($this->appinfo['signature_token'], $this->appinfo['encoding_aes_key'], $this->appinfo['appid']);
        $xmlData = file_get_contents('php://input');
        $msg = '';
        $pc->decryptMsg(input('msg_signature'), input('timestamp'), input('nonce'), $xmlData, $msg);

        return Common::fromXml($msg);
    }

    /**
     * 验证消息signature
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

    /**上传临时图片，保存mediaId */
    public function getMediaId($realPath)
    {
        $img = WxImg::where(['img_local_url' => $realPath])->find();

        if ($img) {
            if ($img['expire_in'] < time()) {// 已过期
                $res = (new WxAPI($this->appinfo['appid']))->uploadMedia($realPath);
                if (isset($res['media_id'])) {
                    WxImg::where(['img_local_url' => $realPath])->update([
                        'media_id' => $res['media_id'],
                        'expire_in' => time() + 3600 * 24 * 3
                    ]);
                    return $res['media_id'];
                }
            } else {
                return $img['media_id'];
            }
        } else {
            $res = (new WxAPI($this->appinfo['appid']))->uploadMedia($realPath);
            if (isset($res['media_id'])) {
                WxImg::create([
                    'img_local_url' => $realPath,
                    'media_id' => $res['media_id'],
                    'expire_in' => time() + 3600 * 24 * 3
                ]);
                return $res['media_id'];
            }
        }
    }

    /**
     * 自动回复
     * @param array $msgFrom 消息来源
     */
    public function autoSend($msgFrom, $msgType, $msgBody)
    {
        $content = [
            'ToUserName' => $msgFrom['FromUserName'],
            'FromUserName' => $msgFrom['ToUserName'],
            'CreateTime' => time(),
            'MsgType' => $msgType,
        ];

        $content = array_merge($content, $msgBody);
        die(Common::toXml($content));
    }
}
