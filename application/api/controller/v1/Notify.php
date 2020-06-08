<?php
namespace app\api\controller\v1;

use app\base\service\WxMsg;
use app\base\controller\Base;
use app\base\service\WxAPI;
use think\Log;

class Notify extends Base
{

    public function receive()
    {
        $wxMsg = new WxMsg(input('appid'));
    
        $wxMsg->checkSignature();
        $msgFrom = $wxMsg->getMsg();
        $msgTo = $wxMsg->msgHandler($msgFrom);        
        if($msgTo['type']=='gzh'){
            $wxMsg->autoSend($msgFrom,  $msgTo['MsgType'], ['Content' => $msgTo['content']]);
        }
    
        die('success');
    }
    
    public function createMenu()
    {
        $data = '{
            "button": [
                {
                    "type": "miniprogram",
                    "name": "打榜应援",
                    "url": "https://mp.weixin.qq.com/s/V-Zw-FDPKLKY4GJfBdZS7w",
                    "appid": "wx7dc912994c80d9ac",
                    "pagepath":"pages/open/open"
                },
                {
                    "type": "view",
                    "name": "购买礼物",
                    "url": "https://ouridol.anaculture.com/#/pages/recharge/recharge"
                }
            ]
        }';
    
        dump((new WxAPI('wx3120fe6dc469ae29'))->createMenu($data));
    }
    
}
