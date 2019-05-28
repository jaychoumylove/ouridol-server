<?php
namespace app\api\controller\v1;

use app\base\service\WxMsg;
use app\base\controller\Base;
use think\Log;
use app\base\service\WxAPI;

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

        $media_id = $wxMsg->getMediaId(ROOT_PATH . 'public/uploads/cust-0.jpg');

        if (isset($msg['Content'])) {
            if ($msg['Content'] == '1') {
                // 充值
                $media_id = $wxMsg->getMediaId(ROOT_PATH . 'public/uploads/cust-1.jpg');
            } else if ($msg['Content'] == '2') {
                // 打卡
                $media_id = $wxMsg->getMediaId(ROOT_PATH . 'public/uploads/cust-2.jpg');
            }
        }

        $ret = (new WxAPI(input('appid')))->sendCustomerMsg(
            $msg['FromUserName'],
            'image',
            [
                'media_id' => $media_id
            ]
        );
        Log::record(json_encode($ret));
        // 1 Rhb7Ve9i_o4CTDHFEEDLxLrezUmYLgtHJX4ygFnU6ByqyfNKc7AqO4fV4xbOW_0b 1558604812
        // 2 OcmHYC1bg8SEP0RQBEJixn4ob6a-fTNba6KIwBXgHf5P0FsDWigvNnwfe0dbu0UF 1558604866
        // 3 lK7m2JjQpaoYn20rXe8D2-DbT3Ne9a7QKKp1jWkSopqIQMNZ1MzxE2CJAueJNaqW 1558604886

        // $wxMsg->autoSend($msg, 'text', [
        //     'Content' =>
        //     "欢迎！回复：\n1 签到\n2 补充能量\n3 人工服务",
        // ]);

        die('success');
    }
}
