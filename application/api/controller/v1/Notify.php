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
        // $content = "<a href='https://rank.xiaolishu.com/#/?token=" . $msg['SessionFrom'] . "'>你好！</a>";

        $redirectUrl = urlencode('https://rank.xiaolishu.com/api/v1/notify/auth');

        // $ret = (new WxAPI(input('appid')))->sendCustomerMsg(
        //     $msg['FromUserName'],
        //     'text',
        //     [
        //         'content' => "你好~"
        //     ]
        // );

        // if (isset($ret['errcode'])) {
            // 被动回复
        $wxMsg->autoSend($msg, 'text', [
            'Content' =>
            "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx00cf0e6d01bb8b01&redirect_uri=$redirectUrl&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'>链接</a>",
        ]);
        // }
        die('success');
    }

    public function getAuth()
    {
        $code = input('code');
        $state = input('state');

        $ret = (new WxAPI('wx00cf0e6d01bb8b01'))->getAuth($code);

        // Common::res(['data' => $ret]);

        Log::record(json_encode($ret));
    }

    
}
