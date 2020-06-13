<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class User extends Base
{
    const USER_ADMIN = 1; // type 为 1 时，该用户为管理员
    const USER_FORBIDDEN = 2; // type 为 2 时，该用户被永久禁言
    const FOREVER_FORBIDDEN = 'ever'; // 永久禁言

    public function UserStar()
    {
        return $this->hasOne('UserStar', 'user_id', 'id', [], 'LEFT');
    }
    public function UserExt()
    {
        return $this->hasOne('UserExt', 'user_id', 'id', [], 'LEFT');
    }

    public function Sprite()
    {
        return $this->hasOne('UserSprite', 'user_id', 'id', [], 'LEFT');
    }

    /**
     * 创建用户
     * @return integer uid 用户id
     */
    public static function searchUser($data)
    {
        Db::startTrans();
        try {
            if ($data['platform'] == 'APP') {
                $openidType = 'openid_app';
            } else if ($data['platform'] == 'H5') {
                $openidType = 'openid_h5';
            } else if ($data['platform'] == 'MP-WEIXIN' || $data['platform'] == 'MP-QQ') {
                $openidType = 'openid';
            }
            $user = self::get([$openidType => $data['openid']]);
            if (!$user) {
                // 创建新用户
                // User
                $user = self::create([
                    $openidType => isset($data['openid']) ? $data['openid'] : null,
                    'session_key' => isset($data['session_key']) ? $data['session_key'] : null,

                    'platform' => isset($data['platform']) ? $data['platform'] : null,
                    'model' => isset($data['model']) ? $data['model'] : null,
                    'type' => isset($data['type']) ? $data['type'] : 0,
                ]);
                // UserCurrency
                $currency = [
                    'uid' => $user['id'],
                ];
                if (isset($data['type']) && $data['type'] != 0) {
                    // 0 普通用户
                    $currency['coin'] =  100000;
                    $currency['stone'] =  300;
                } else {
                    $currency['coin'] =  100;
                    $currency['stone'] =  3;
                }
                UserCurrency::create($currency);

                // UserExt
                UserExt::create([
                    'user_id' => $user['id'],
                    'left_time' => json_encode([0, 0, 0, 0, 0]),
                ]);

                // UserSprite
                UserSprite::create([
                    'user_id' => $user['id'],
                    'settle_time' => time(),
                ]);
            } else {
                if (isset($data['session_key'])) {
                    self::where('id', $user['id'])->update(['session_key' => $data['session_key']]);
                }
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $user['id'];
    }

    /**保存用户信息 */
    public static function saveUserInfo($data)
    {
        if ($data['platform'] == 'APP') {
            $openidType = 'openid_app';
        } else if ($data['platform'] == 'H5') {
            $openidType = 'openid_h5';
        } else if ($data['platform'] == 'MP-WEIXIN' || $data['platform'] == 'MP-QQ') {
            $openidType = 'openid';
        }

        // 寻找是否有已存在的账号unionid相同但openid为空
        if (isset($data['unionid']) && $data['unionid']) {
            $optherPlatformUid = self::where('unionid', $data['unionid'])->where($openidType, 'null')->value('id');
        } else {
            $data['unionid'] = null;
        }
        $currentUid = self::where($openidType, $data['openid'])->value('id');
        if (isset($optherPlatformUid) && $optherPlatformUid) {
            // 在其他平台已有账号
            // 删除当前用户
            self::where('id', $currentUid)->delete(true);
            // UserCurrency
            UserCurrency::where('uid', $currentUid)->delete(true);
            // UserExt
            UserExt::where('user_id', $currentUid)->delete(true);
            // UserSprite
            UserSprite::where('user_id', $currentUid)->delete(true);

            $currentUid = $optherPlatformUid;
        }

        $user = self::get($currentUid);
        $update = [
            $openidType => isset($data['openid']) ? $data['openid'] : null,
            'unionid' => isset($data['unionid']) ? $data['unionid'] : null,
        ];

        if ($data['platform'] == 'MP-WEIXIN' || $data['platform'] == 'MP-QQ' || !$user['nickname']) {
            // 如果是微信小程序或者用户没有授权则更新用户资料
            $update = array_merge($update, [
                'nickname' => isset($data['nickname']) ? $data['nickname'] : null,
                'avatarurl' => isset($data['avatarurl']) ? $data['avatarurl'] : null,
                'gender' => isset($data['gender']) ? $data['gender'] : null,
                'language' => isset($data['language']) ? $data['language'] : null,
                'city' => isset($data['city']) ? $data['city'] : null,
                'province' => isset($data['province']) ? $data['province'] : null,
                'country' => isset($data['country']) ? $data['country'] : null,
            ]);            
            if(!$update['nickname']) unset($update['nickname']);
        }
        self::where('id', $currentUid)->update($update);
        return self::get($currentUid);
    }

    /**创建虚拟用户 */
    public static function createVirtualUser($data)
    {
        $vrNickname = OtherFakeUser::where('1=1')->orderRaw('rand()')->value('nickname');
        $vrAvatar = OtherFakeUser::where('1=1')->orderRaw('rand()')->value('avatar');

        return self::searchUser([
            'platform' => $data['platform'],
            'openid' => $data['openid'],
            'unionid' => $data['unionid'],
            'nickname' => $vrNickname,
            'avatarurl' => $vrAvatar,
            'type' => 1 // 虚拟用户type
        ]);
    }

    /**创建机器人用户 */
    public static function createAndroid($vrNickname, $vrAvatar)
    {
        $rdCode = Common::getRandCode(24);

        return self::searchUser([
            'openid' => $rdCode,
            'unionid' => $rdCode,
            'nickname' => $vrNickname,
            'avatarurl' => $vrAvatar,
            'type' => 5 // 机器人用户type
        ]);
    }

    /**随机获取一个圈子内的机器人 */
    public static function getOneAndroid($starid, $limit_hot)
    {
        $uid = Db::name('user_star')->alias('s')->join('user u', 'u.id = s.user_id')
            ->where('u.type', 5)->where('s.star_id', $starid)->where('s.thisweek_count', '<', $limit_hot)
            ->orderRaw('rand()')->value('u.id');

        if (!$uid) Common::res(['code' => 1, 'msg' => '该圈子未找到Android']);

        return $uid;
    }

    /**
     * 禁言
     * @param      $userId
     * @param      $forbiddenId
     * @param int  $time
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function forbidden ($userId, $forbiddenId, $time = -1)
    {
        $check = self::checkForbidden($forbiddenId);
        if (true == $check) return true;

        $model = new self();

        $self = $model::get($userId);

        if ($self['type'] == self::USER_ADMIN) {
            $isDone = $model->forbiddenUser($forbiddenId, self::FOREVER_FORBIDDEN);

            if (empty($isDone)) Common::res(['code' => 1, 'msg' => '禁言失败']);

            return true;
        }

        $userExt = UserStar::where(['user_id' => $userId])->find();

        if (empty($userExt)) Common::res(['code' => 1, 'msg' => '禁言失败']);

        if ($userExt['captain'] == UserStar::CAPTAIN) {
            if ((int) $time < 0) Common::res(['code' => 1, 'msg' => '请选择禁言时间']);
            $isDone = $model->forbiddenUser($forbiddenId, $time);

            if (empty($isDone)) Common::res(['code' => 1, 'msg' => '禁言失败']);

            return true;
        }

        Common::res(['code' => 1, 'msg' => '你无权禁言']);
    }

    /**
     * 检查是否是禁言状态
     * @param      $forbiddenId
     * @param bool $time
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function checkForbidden ($forbiddenId, $time = false)
    {
        $user = self::get($forbiddenId);
        if ($user['type'] == self::USER_FORBIDDEN) return $time ? self::FOREVER_FORBIDDEN: true;

        $userExit = UserExt::where(['user_id' => $forbiddenId])->find();
        if (empty($userExit)) return false;

        if (empty($userExit['forbidden_time'])) return false;
        if ($userExit['forbidden_time'] > time()) return $time ? $userExit['forbidden_time']: true;

        return false;
    }

    /**
     * 禁言
     * @param $forbiddenId
     * @param $type
     * @return User|UserExt
     */
    public static function forbiddenUser ($forbiddenId, $type)
    {
        if ($type == self::FOREVER_FORBIDDEN) {
            return self::where('id', $forbiddenId)->update(['type' => self::USER_FORBIDDEN]);
        }

        if ($type > -1) {
            $forbiddenTimeCfg = Cfg::getCfg(Cfg::FORBIDDEN_TIME);

            $time = $forbiddenTimeCfg[$type]['key'];

            $forbiddenTime = strtotime(sprintf('+%s', $time));

            return UserExt::where(['user_id' => $forbiddenId])->update(['forbidden_time' => $forbiddenTime]);
        }
    }

    /**
     * 618活动领取"怦然心动"
     * @param $uid
     */
    public static function active618gift ($uid)
    {
        UserItem::addItem($uid, UserItem::ACTIVE618ITEM);

        Rec::addRec(['type' => Rec::ACTIVE618GIFT, 'user_id' => $uid]);
    }
}
