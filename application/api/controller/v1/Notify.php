<?php
namespace app\api\controller\v1;

use app\base\service\WxMsg;
use app\base\controller\Base;
use think\Log;

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



        // $ret = (new WxAPI(input('appid')))->sendCustomerMsg(
        //     $msg['FromUserName'],
        //     'text',
        //     [
        //         'content' => "你好~"
        //     ]
        // );

        if ($msg['Content'] == '签到') {
            $redirectUrl = urlencode('https://rank.xiaolishu.com/#/pages/signin/signin');
            $wxMsg->autoSend($msg, 'text', [
                'Content' =>
                "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx00cf0e6d01bb8b01&redirect_uri=$redirectUrl&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'>链接</a>",
            ]);
        } else if ($msg['Content'] == '充值') {
            $redirectUrl = urlencode('https://rank.xiaolishu.com/#/pages/recharge/recharge');

            $wxMsg->autoSend($msg, 'text', [
                'Content' =>
                "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx00cf0e6d01bb8b01&redirect_uri=$redirectUrl&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'>链接</a>",
            ]);
        } else {
            $wxMsg->autoSend($msg, 'text', [
                'Content' =>
                "回复：签到或充值",
            ]);
        }



        die('success');
    }
}
