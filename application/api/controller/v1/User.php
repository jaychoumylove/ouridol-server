<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\User as UserModel;
use app\base\service\Common;
use app\api\service\User as UserService;
use app\api\model\UserItem as UserItemModel;
use app\api\model\UserCurrency;
use app\api\model\UserStar;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\UserSprite;
use app\api\model\CfgShare;
use app\api\model\Cfg;
use app\base\service\WxAPI;
use app\api\model\CfgSignin;
use GatewayWorker\Lib\Gateway;
use app\api\model\RecStarChart;
use app\api\model\CfgUserLevel;

class User extends Base
{
    /**
     * 用户登录
     * 获取到用户的openid
     */
    public function login()
    {
        // 登录code 小程序 公众号H5
        $code = $this->req('code');

        $res['platform'] = $this->req('platform', 'require', 'MP-WEIXIN'); // 平台
        $res['model'] = $this->req('model'); // 手机型号

        if ($code) {
            // 以code形式获取openid
            $res = array_merge($res, (new UserService())->wxGetAuth($code, $res['platform']));
        } else {
            $res['openid'] = $this->req('openid');
        }
        
        $uid = UserModel::searchUser($res);
        $token = Common::setSession($uid);

        Common::res(['msg' => '登录成功', 'data' => ['token' => $token, 'package' => $res]]);
    }

    /**授权&保存用户信息 */
    public function saveInfo()
    {
        $type = $this->req('type', 'require', 0);

        if ($type == 0) {
            // 小程序授权
            // 解密形式
            $encryptedData = $this->req('encryptedData', 'require');
            $iv = $this->req('iv', 'require');

            $this->getUser();

            $appid = (new WxAPI())->appinfo['appid'];
            $sessionKey = UserModel::where('id', $this->uid)->value('session_key');

            // 解密encryptedData
            $res = Common::wxDecrypt($appid, $sessionKey, $encryptedData, $iv);
            if ($res['errcode']) Common::res(['code' => 1, 'msg' => $res['data']]);

            // 保存
            foreach ($res['data'] as $key => $value) {
                $saveData[strtolower($key)] = $value;
            }
        } else {
            // 公众号和app授权
            // 通过openid和access_token
            $openid = $this->req('openid', 'require');
            $access_token = $this->req('access_token', 'require');
            $res = (new WxAPI())->getUserInfo($openid, $access_token);
            if (isset($res['errcode'])) Common::res(['code' => 1, 'msg' => $res]);

            $saveData = $res;
            $saveData['avatarurl'] = $res['headimgurl'];
            $saveData['gender'] = $res['sex'];
        }

        $saveData['platform'] = $this->req('platform', 'require', 'MP-WEIXIN'); // 平台

        // 包含用户信息和unionid的数据集合
        $data = UserModel::saveUserInfo($saveData);
        $token = Common::setSession($data['id']);
        Common::res(['data' => ['userInfo' => $data, 'token' => $token]]);
    }

    public function getInfo()
    {
        $this->getUser();
        $uid = input('user_id', null);
        if (!$uid) {
            $uid = $this->uid;
        }

        $res = UserModel::where(['id' => $uid])->field('id,nickname,avatarurl,type')->find();
        $res['userStar'] = UserStar::where('user_id', $uid)->field('total_count,thismonth_count,thisweek_count')->find();
        $res['level'] = UserSprite::where('user_id', $uid)->value('sprite_level');
        Common::res(['data' => $res]);
    }

    /**
     * 获取用户所有货币数量
     */
    public function getCurrency()
    {
        $this->getUser();
        $res = UserCurrency::getCurrency($this->uid);

        Common::res(['data' => $res]);
    }

    public function getStar()
    {
        $this->getUser();

        $res = UserStar::with('Star')->where(['user_id' => $this->uid])->order('id desc')->find();
        unset($res['star']['create_time']);
        Common::res(['data' => $res['star']]);
    }

    /**
     * 获取用户道具
     */
    public function getItem()
    {
        $this->getUser();

        $item = UserItemModel::getItem($this->uid);
        Common::res(['data' => $item]);
    }

    public function invitList()
    {
        $type = input('type', 0);
        $page = input('page', 1);
        $size = input('size', 10);

        $this->getUser();
        $res = UserRelation::fixByType($type, $this->uid, $page, $size);

        Common::res(['data' => [
            'list' => $res,
            'award' => Cfg::getCfg('invitAward'),
            'hasInvitcount' => UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['in', [1, 2]]])->count()
        ]]);
    }

    public function invitAward()
    {
        $ral_user_id = input('ral_user_id');
        if (!$ral_user_id) Common::res(['code' => 100]);
        $this->getUser();

        (new UserService())->getInvitAward($ral_user_id, $this->uid);
        Common::res([]);
    }

    /**
     * 绑定推送客户端id
     */
    public function bindClientId()
    {
        $client_id = input('client_id');
        if (!$client_id) Common::res(['code' => 100]);

        $this->getUser();

        Gateway::bindUid($client_id, $this->uid);
        Common::res([]);
    }

    public function stealTime()
    {
        $this->getUser();
        $res = UserExt::get(['user_id' => $this->uid]);
        $leftTime = json_decode($res['left_time']);
        foreach ($leftTime as &$value) {
            $time =  Cfg::getCfg('stealLimitTime') - (time() - $value);
            if ($time < 0) {
                $time = 0;
            }
            $value = $time;
        }
        Common::res(['data' => $leftTime]);
    }

    public function sayworld()
    {
        $content = $this->req('content', 'require');
        $this->getUser();
        // 发言内容校验
        if (input('platform') == 'MP-WEIXIN') {
            RecStarChart::verifyWord($content);
        }
        // 扣除喇叭
        (new UserService())->change($this->uid, [
            'trumpet' => -1
        ], [
            'type' => 3
        ]);

        $user = UserModel::where(['id' => $this->uid])->field('nickname,avatarurl')->find();
        // 推送socket消息
        Gateway::sendToAll(json_encode([
            'type' => 'sayworld',
            'data' => [
                'avatarurl' => $user['avatarurl'],
                'content' => $content,
                'nickname' => $user['nickname'],
            ],
        ], JSON_UNESCAPED_UNICODE));

        Common::res([]);
    }

    /**退出偶像圈 */
    public function exit()
    {
        $this->getUser();
        UserStar::exit($this->uid);
        Common::res([]);
    }

    public function signin()
    {
        $this->getUser();

        $cfg = CfgSignin::all();

        $res = (new UserService())->signin($this->uid);
        $res['cfg'] = $cfg;

        Common::res(['data' => $res]);
    }

    /**礼物兑换能量 */
    public function recharge()
    {
        $item_id = input('item_id');
        $num = input('num');
        if (!$item_id || !$num || $num < 0) Common::res(['code' => 100]);
        $this->getUser();

        UserItemModel::recharge($this->uid, $item_id, $num);

        Common::res([]);
    }

    /**加好友 */
    public function addFriend()
    {
        $user_id = input('user_id');
        if (!$user_id || $user_id == 'undefined') Common::res(['code' => 100]);

        $this->getUser();

        UserRelation::addFriend($this->uid, $user_id);

        Common::res();
    }

    /**删好友 */
    public function delFriend()
    {
        $user_id = input('user_id');
        if (!$user_id || $user_id == 'undefined') Common::res(['code' => 100]);

        $this->getUser();

        UserRelation::delFriend($this->uid, $user_id);
        Common::res();
    }

    /**送灵丹给他人 */
    public function sendStoneToOther()
    {
        $user_id = input('user_id');

        $num = input('num');
        $type = input('type', 'stone');
        if (!$user_id || !$num || $user_id == 'undefined') Common::res(['code' => 100]);
        $this->getUser();

        UserCurrency::sendStoneToOther($this->uid, $user_id, $num, $type);
        Common::res();
    }


    public function sendItemToOther()
    {
        $user_id = input('user_id');
        $item_id = input('item_id'); // 礼物id
        if (!$user_id || !$item_id || $user_id == 'undefined') Common::res(['code' => 100]);

        $num = input('num', 1);
        $this->getUser();

        UserItemModel::sendItemToOther($this->uid, $user_id, $num, $item_id);
        Common::res();
    }

    public function forbidden()
    {
        $user_id = input('user_id');

        $type = 2;

        $isDone = UserModel::where('id', $user_id)->update(['type' => $type]);
        if ($isDone) {
            Common::res();
        } else {
            Common::res(['code' => 1]);
        }
    }
    
    public function level()
    {
        //$this->getUser();
        $user_id = $this->req('user_id', 'integer');
        
        $count = UserStar::where('user_id', $user_id)->order('id desc')->value('total_count');
        $res['level'] = CfgUserLevel::where('total', '<=', $count)->max('level');
        
        $nextCount = CfgUserLevel::where('total', '>', $count)->order('level asc')->value('total');
        $res['gap'] = $nextCount - $count;
        if ($res['gap'] < 0) $res['gap'] = 0;
        Common::res(['data' => $res]);
    }
}
