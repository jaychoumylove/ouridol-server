<?php

namespace app\base\service;

use app\api\model\GzhTemplate;
use app\api\model\GzhUser;
use app\api\model\User;
use app\api\model\UserExt;
use app\api\model\WxImg;
use think\Log;
use app\api\model\Star;

class WxMsg
{
    private $appinfo;
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
        $img = WxImg::where(['appid' => $this->appinfo['appid'], 'img_local_url' => $realPath])->find();

        if ($img) {
            if ($img['expire_in'] < time()) { // 已过期
                $res = (new WxAPI($this->appinfo['appid']))->uploadMedia($realPath);
                if (isset($res['media_id'])) {
                    WxImg::where(['appid' => $this->appinfo['appid'], 'img_local_url' => $realPath])->update([
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
                    'appid' => $this->appinfo['appid'],
                    'img_local_url' => $realPath,
                    'media_id' => $res['media_id'],
                    'expire_in' => time() + 3600 * 24 * 3
                ]);
                return $res['media_id'];
            }
        }
    }

    /**
     * 自动(被动)回复
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

    public function msgHandler($msgFrom)
    {    
        if($this->appinfo['type']=='gzh') return $this->msgGzh($msgFrom);
        elseif($this->appinfo['type']=='miniapp') return $this->msgMiniapp($msgFrom);
    }
    
    /**
     * 处理受到的消息
     * 并获取需要回复的消息
     */
    public function msgMiniapp($msg)
    {
        if ($msg['MsgType'] == 'text') {
            $media_id = $this->getMediaId(ROOT_PATH . 'public/uploads/cust-0.jpg');
        
            if (isset($msg['Content'])) {
                if ($msg['Content'] == '1') {
                    // 充值
                    $media_id = $this->getMediaId(ROOT_PATH . 'public/uploads/cust-1.jpg');
                } else if ($msg['Content'] == '2') {
                    // 打卡
                    $media_id = $this->getMediaId(ROOT_PATH . 'public/uploads/cust-2.jpg');
        
                }else if ($msg['Content'] == '3') {
                    // 加群
                    $media_id = $this->getMediaId(ROOT_PATH . 'public/uploads/cust-3.jpg');
                }
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
    
    
    /**
     * 处理受到的消息
     * 并获取需要回复的消息
     */
    public function msgGzh($msg)
    {
        // 用户id
        $user_id = $this->getUser($msg['FromUserName']);

        $content = '欢迎！';
        if ($msg['MsgType'] == 'text') {
            // 文本消息
            if (strpos($msg['Content'],'表白') === 0) {
                
                //查找爱豆
                $star_name = str_replace("表白","",$msg['Content']);
                $star_id = Star::where('name',$star_name)->value('id');
                if(!$star_id) $content = "未找到你表白的爱豆，请先进入【我们的偶像】小程序打榜\n<a data-miniprogram-appid='wx7dc912994c80d9ac' data-miniprogram-path='/pages/open/open' href='https://mp.weixin.qq.com/s/NRovcmTDj_Tziu8qe_DY9Q'>点击这里给爱豆打榜</a>";
                elseif (!$user_id) $content = "未找到用户，可能是因为您还未进入【我们的偶像】小程序打榜\n<a data-miniprogram-appid='wx7dc912994c80d9ac' data-miniprogram-path='/pages/open/open' href='https://mp.weixin.qq.com/s/NRovcmTDj_Tziu8qe_DY9Q'>点击这里给爱豆打榜</a>";
                else {
                    $content = UserExt::biaobai($user_id,$star_id,$star_name); 
                    
                    $media_id = $this->getMediaId(ROOT_PATH . 'public/uploads/biaobai520.png');
                    $ret = (new WxAPI(input('appid')))->sendCustomerMsg($msg['FromUserName'], 'image', [
                        'media_id' => $media_id
                    ]);
                }
                
            } else if ($msg['Content'] == '签到' || $msg['Content'] == 2) {
                
                // 每日签到
                $content = "<a href='https://ouridol.anaculture.com/#/pages/signin/signin'>点这里每日签到</a>";
                
            }
        } else if ($msg['MsgType'] == 'event') {
            // 事件消息
            if ($msg['EventKey'] == 'CLICK_kefu') {
                
                $content = " 【联系客服】\n您的用户ID为：" . ($user_id * 1234 ? $user_id * 1234 : '') . "\n请加客服（薇薇姐2）微信：ouridol2\n请一定注明反馈的问题或者建议，否则可能会被忽略哦！";
            
            } else if ($msg['EventKey'] == 'CLICK_lianxi') {
                
                $content = " 【商务合作】\n寻求合作及赞助可发送邮件：104460712@qq.com\n请一定注明公司、姓名、以及合作内容、品牌，否则可能会被忽略哦！";
                
            } else if ($msg['EventKey'] == 'biaobai520') { //

                $media_id = $this->getMediaId(ROOT_PATH . 'public/uploads/biaobai520.png');
                $ret = (new WxAPI(input('appid')))->sendCustomerMsg($msg['FromUserName'], 'image', [
                    'media_id' => $media_id
                ]);
            } 
            else if ($msg['Event'] == 'subscribe') {
                
                // 关注
                $content = '谢谢你那么可爱还关注了我~';
                
            } else if ($msg['Event'] == 'subscribe') {
                
                // if ($user_id) {
                //     // 匹配到用户

                //     GzhTemplate::getTemplate(1, $msg['FromUserName'], 'wx7dc912994c80d9ac', 'https://idolzone.cyoor.com', [
                //         '账号绑定成功', 
                // User::where('id', $user_id)->value('nickname'),
                //     ]);
                // }
                // $data = '{"touser":"oVR6g0keVmckJh257vfbtCMxYj0M","template_id":"4JUFNAzJFEbNJo5dOgEwI3bKSeOgS04q33J3110lz08","url":"https://idolzone.cyoor.com/","miniprogram":{"appid":"wx7dc912994c80d9ac"},"data":{"first":{"value":"你已绑定账号成功"},"keyword1":{"value":"巧克力"},"keyword2":{"value":"绑定成功,小程序订阅功能已开启"},"remark":{"value":">>>点击即可回到小程序订阅数据推送"}}}';
                // $wxApi = new WxAPI($this->appinfo['appid']);
                // $wxApi->sendMessageGzh($data);
            }
        }

        $content .= "\n";
        $content .= "\n你可能对以下内容感兴趣：";
        $content .= "\n回复“签到”领取每日签到奖励";
        $content .= "\n<a href='https://ouridol.anaculture.com/#/pages/recharge/recharge'>购买礼物</a>";
        $content .= "\n<a href='https://ouridol.anaculture.com/#/pages/prop/buy/buy'>购买道具</a>";
        $content .= "\n<a href='https://mp.weixin.qq.com/s/A2SmRYS5Xt1Qeh8tQVIxdg'>榜单福利</a>";
        $content .= "\n<a href='https://mp.weixin.qq.com/s/NRovcmTDj_Tziu8qe_DY9Q'>打榜攻略</a>";

        $content .= "\n";
        $content .= "\n<a data-miniprogram-appid='wx7dc912994c80d9ac' data-miniprogram-path='/pages/open/open' href='https://mp.weixin.qq.com/s/NRovcmTDj_Tziu8qe_DY9Q'>点击这里给爱豆打榜</a>";
        

        // 关注/取关
        $subscribe = (int) !($msg['MsgType'] == 'event' && $msg['Event'] == 'unsubscribe');
        GzhUser::gzhSubscribe(input('appid'), $user_id, $msg['FromUserName'], $subscribe);

        return ['type'=>'gzh','MsgType'=>'text','content'=>$content];
    }

    /**
     * 公众号通过unionid获取唯一的用户id,需要关注公众号
     * @param string $openid FromUserName
     */
    public function getUser($openid)
    {
        $wxApi = new WxAPI($this->appinfo['appid']);
        $res = $wxApi->getUserInfocgi($openid);        
        if (isset($res['unionid'])) {
            return User::where(['unionid' => $res['unionid']])->value('id');
        }
    }

    /**下载图片 */
    public function download($url)
    {
        $content = file_get_contents($url);
        $filePath = ROOT_PATH . 'public/uploads/' . time() . mt_rand(10000, 99999);
        file_put_contents($filePath, $content);
        return $filePath;
    }
}
