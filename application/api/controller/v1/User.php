<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\User as UserModel;
use app\base\service\Common;
use app\api\service\User as UserService;
use app\api\model\UserItem as UserItemModel;
use GatewayClient\Gateway;
use app\api\model\UserCurrency;
use app\api\model\UserStar;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\UserSprite;
use app\api\model\CfgShare;
use app\api\model\Cfg;

class User extends Base
{
    /**用户登录 */
    public function login()
    {
        $js_code = input('js_code'); // 微信登录
        // $unionid = input('unionid'); // APP登录
        if (!$js_code) Common::res(['code' => 100]);

        $res = (new UserService())->wxGetAuth($js_code);

        $res['platform'] = input('platform', null); // 平台
        $res['model'] = input('model', null); // 手机型号
        $uid = UserModel::saveUser($res);
        $token = Common::setSession($uid);

        Common::res(['msg' => '登录成功', 'data' => ['token' => $token]]);
    }

    /**保存用户信息 */
    public function saveInfo()
    {
        $this->getUser();

        $data['nickname'] = input('nickName');
        $data['gender'] = input('gender');
        $data['language'] = input('language');
        $data['city'] = input('city');
        $data['province'] = input('province');
        $data['country'] = input('country');
        $data['avatarurl'] = input('avatarUrl');

        UserModel::where(['id' => $this->uid])->update($data);
        Common::res([]);
    }

    public function getInfo()
    {
        $this->getUser();
        $uid = input('user_id', null);
        if (!$uid) {
            $uid = $this->uid;
        }

        $res = UserModel::where(['id' => $uid])->field('id,nickname,avatarurl')->find();
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

        $this->getUser();
        $res = UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['neq', 0]])->select();
        $res = UserRelation::fixByType($type, $res, $this->uid);
        Common::res(['data' => [
            'list' => $res,
            'award' => Cfg::getCfg('invitAward'),
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
        $content = input('content');
        if (!$content) Common::res(['code' => 100]);
        $this->getUser();
        // 扣除喇叭
        (new UserService())->change($this->uid, [
            'trumpet' => -1
        ], [
            'type' => 3
        ]);

        $avatarUrl = UserModel::where(['id' => $this->uid])->value('avatarurl');
        // 推送socket消息
        Gateway::sendToAll(json_encode([
            'type' => 'sayworld',
            'data' => [
                'avatarurl' => $avatarUrl,
                'content' => $content,
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
}
