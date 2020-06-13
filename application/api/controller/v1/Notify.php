<?php
namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\model\GzhUser;
use app\api\service\User as UserService;
use app\base\service\WxMsg;
use app\base\controller\Base;
use app\base\service\WxAPI;
use think\Db;

class Notify extends Base
{

    private $wxMsg;
    public function receive()
    {
        $this->wxMsg = new WxMsg(input('appid'));

        $this->wxMsg->checkSignature();
        $msgFrom = $this->wxMsg->getMsg();
        $this->msgHandler($msgFrom);

        die('success');
    }

    private function msgHandler($msgFrom)
    {
        if($this->wxMsg->appinfo['type']=='gzh') $this->msgGzh($msgFrom);
        elseif($this->wxMsg->appinfo['type']=='miniapp') $this->msgMiniapp($msgFrom);
    }

    /**
     * 处理小程序到的消息
     * 并获取需要回复的消息
     */
    private function msgMiniapp($msg)
    {
        $Content = "你可能对以下内容感兴趣：\n";
        $Content .= "关注公众号，公众号内回复“1”领取每日签到奖励\n";

        //发送文本消息
        $ret = (new WxAPI(input('appid')))->sendCustomerMsg(
            $msg['FromUserName'],
            'text',
            [
                'content' => $Content
            ]
        );

        //发送不同的信息回复不同的内容
        if (isset($msg['Content'])) {
            if ($msg['Content'] == '1') {
                // 充值
                $media_id = $this->wxMsg->getMediaId(ROOT_PATH . 'public/uploads/cust-1.jpg');
            } else if ($msg['Content'] == '2') {
                // 打卡
                $media_id = $this->wxMsg->getMediaId(ROOT_PATH . 'public/uploads/cust-2.jpg');

            }else if ($msg['Content'] == '3') {
                // 加群
                $media_id = $this->wxMsg->getMediaId(ROOT_PATH . 'public/uploads/cust-3.jpg');
            }

            $ret = (new WxAPI(input('appid')))->sendCustomerMsg(
                $msg['FromUserName'],
                'image',
                [
                    'media_id' => $media_id
                ]
            );
        }
    }

    //公众号处理
    private function msgGzh($msg){

        $Content = '';

        if ($msg['MsgType'] == 'text' && isset($msg['Content']) && ($msg['Content'] == '2' || $msg['Content'] == '签到')) {
            $Content .= $this->signDay($msg);

        } elseif (isset($msg['Event']) && $msg['Event'] == 'CLICK' && $msg['EventKey'] == 'CLICK_kefu') { //按钮操作
            $Content = " 【联系客服】\n请加客服（薇薇姐2）微信：ouridol2\n请一定注明反馈的问题或者建议，否则可能会被忽略哦！";

        } elseif (isset($msg['Event']) && $msg['Event'] == 'CLICK' && $msg['EventKey'] == 'CLICK_lianxi') { //按钮操作
            $Content = " 【商务合作】\n寻求合作及赞助可发送邮件：104460712@qq.com\n请一定注明公司、姓名、以及合作内容、品牌，否则可能会被忽略哦！";

        } elseif (isset($msg['Event']) && ($msg['Event'] == 'subscribe' || $msg['Event'] == 'unsubscribe')) { //关注取关
            $this->getUserId($msg);
        }

        $Content .= "你可能对以下内容感兴趣：\n";
        $Content .= "回复“签到”领取每日签到奖励\n";
        $Content .= "<a href='https://ouridol.anaculture.com/#/pages/recharge/recharge'>购买礼物</a>\n";
        $Content .= "<a href='https://ouridol.anaculture.com/#/pages/prop/buy/buy'>购买道具</a>\n";
        $Content .= "<a href='https://mp.weixin.qq.com/s/A2SmRYS5Xt1Qeh8tQVIxdg'>榜单福利</a>\n";
        $Content .= "<a href='https://mp.weixin.qq.com/s/NRovcmTDj_Tziu8qe_DY9Q'>打榜攻略</a>\n";

        $Content .= "\n";
        $Content .= "<a data-miniprogram-appid='wx7dc912994c80d9ac' data-miniprogram-path='/pages/open/open' href='https://mp.weixin.qq.com/s/NRovcmTDj_Tziu8qe_DY9Q'>点击这里给爱豆打榜</a>";


        $this->wxMsg->autoSend($msg, 'text', [
            'Content' => $Content
        ]);
    }

    /**
     * 公众号每日签到
     */
    private function signDay($msg)
    {
        $user_id = $this->getUserId($msg);
        if(!$user_id) return "没有关联到用户，请先到小程序打榜！\n<a data-miniprogram-appid=\"wx7dc912994c80d9ac\" data-miniprogram-path=\"/pages/index/index\">点击此链接去打榜吧~</a>\n----------------------------\n\n";

        return "<a href='https://ouridol.anaculture.com/#/pages/signin/signin'>点这里每日签到</a>";

//        $isSigned = UserExt::where('user_id', $user_id)->whereTime('gzh_signin_time', 'd')->value('id');
//        if ($isSigned) return "你今天已经签到，明日再来！\n----------------------------\n\n";
//
//        // 增加货币
//        $update = ['coin'=>3000,'stone'=>3];
//        Db::startTrans();
//        try {
//            (new UserService)->change($user_id, $update,'公众号签到');
//            UserExt::where('user_id', $user_id)->update(['gzh_signin_time' => time()]);
//            Db::commit();
//            return "签到成功，能量+{$update['coin']}，灵丹+{$update['stone']}\n----------------------------\n\n";
//
//        } catch (\Exception $e) {
//            Db::rollBack();
//            return 'rollBack:' . $e->getMessage();
//        }
    }

    // 获取用户id
    private function getUserId($msg)
    {
        $wxApi = new WxAPI(input('appid'));
        $res = $wxApi->getUserInfocgi($wxApi->appinfo['access_token'],$msg['FromUserName']);
        $user_id = isset($res['unionid']) ? UserModel::where(['unionid' => $res['unionid']])->value('id') : NULL;
        $subscribe = (int) !($msg['MsgType'] == 'event' && $msg['Event'] == 'unsubscribe');//关注还是取关
        GzhUser::gzhSubscribe(input('appid'), $user_id, $msg['FromUserName'], $subscribe);

        return $user_id;
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
