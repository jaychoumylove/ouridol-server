<?php
namespace app\api\controller\v1;

use app\base\service\WxMsg;
use app\base\controller\Base;
use think\Log;
use app\base\service\WxAPI;
use app\base\service\Common;

class Notify extends Base
{

    public function receive()
    {
        $wxMsg = new WxMsg(input('appid'));

        $wxMsg->checkSignature();
        $msg = $wxMsg->getMsg();

        // {"ToUserName":"gh_7c87eaf27f5a",
        // "FromUserName":"oj77y5LIpHuIWUU2kW8BHVP4goPc","CreateTime":"1558089549",
        // "MsgType":"text","Content":"99","MsgId":"22306477788296821"}
        Log::record(json_encode($msg));

        $ret = (new WxAPI(input('appid')))->sendCustomerMsg(
            $msg['FromUserName'],
            'text',
            [
                'content' => "<a href='https://rank.xiaolishu.com?token='>链接</a>",
            ]
        );

        if (isset($ret['errcode'])) {
            // 被动回复
            $wxMsg->autoSend($msg, 'text', [
                'Content' => "<a href='https://rank.xiaolishu.com'>链接</a>",
            ]);
        }
        die('success');
    }
}
