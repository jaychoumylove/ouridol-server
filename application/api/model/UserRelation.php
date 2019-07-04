<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;

class UserRelation extends Base
{
    //

    public function User()
    {
        return $this->belongsTo('User', 'ral_user_id', 'id')->field('id,nickname,avatarurl');
    }

    public function RerUser()
    {
        return $this->belongsTo('User', 'rer_user_id', 'id')->field('id,nickname,avatarurl');
    }

    public static function saveNew($ral_user_id, $rer_user_id)
    {
        if ($ral_user_id == $rer_user_id) return;
        $item = self::get([
            'ral_user_id' => $ral_user_id,
        ]);
        if (!$item) {
            // 非新人
            if (UserStar::get(['user_id' => $ral_user_id])) return;

            self::create([
                'rer_user_id' => $rer_user_id,
                'ral_user_id' => $ral_user_id,
            ]);
        }
    }

    /**新用户加入圈子后 修改关系状态 */
    public static function join($starid, $uid)
    {
        $relation = self::where(['ral_user_id' => $uid, 'status' => ['in',  [0, 1, 2]]])->find();

        if ($relation) {
            self::where(['ral_user_id' => $uid])->update(['status' => 1]);
            // 判断是否结成师徒关系
            UserFather::join($relation['rer_user_id'], $uid);
        }

        self::giveFriend($starid, $uid);
    }

    /**给新加入圈子的用户5个本圈子内的用户作为初始好友 */
    public static function giveFriend($starid, $uid)
    {
        $num = 5;
        $exitUser = self::where('rer_user_id', $uid)->column('ral_user_id');
        $exitUser = array_merge($exitUser, self::where('ral_user_id', $uid)->column('rer_user_id'));

        $userList = UserStar::where(['star_id' => $starid])->where('user_id', 'neq', $uid)->where('user_id', 'not in', $exitUser)
            ->orderRaw('rand()')->limit($num)->column('user_id');

        foreach ($userList as $value) {
            UserRelation::create([
                'rer_user_id' => $uid,
                'ral_user_id' => $value,
                'status' => 3,
            ]);
        }
    }

    /**
     * status 0 邀请的用户 没有加入圈子
     *        1 邀请的用户 已加入圈子 未领取拉新奖励
     *        3 系统分配的圈内用户
     *        4 手动加的用户
     */
    public static function fixByType($type, $uid, $page, $size)
    {
        if ($type == 1) { // 宠物页面邀请列表，统计收益
            // 我邀请的人、、加的好友
            $res = UserRelation::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1, 2, 3, 4]]])->order('create_time desc')->limit(100)->select();
            // 邀请我的人、手动被加的好友
            $ralUser = self::with('RerUser')->where(['ral_user_id' => $uid, 'status' => ['in', [1, 2, 4]]])->limit(100)->select();
            foreach ($ralUser as &$value) {
                $value['user'] = $value['rer_user'];
            }
            $res = array_merge($res, $ralUser);

            if ($res) {
                foreach ($res as $key => &$value) {
                    $update_time = UserStar::where(['user_id' => $value['user']['id']])->value('update_time');
                    if (time() - strtotime($update_time) > 3 * 3600 * 24) {
                        // 不活跃的不能收
                        $value['off'] = true;
                    } else {
                        $value['off'] = false;
                    }

                    $value['sprite'] = ['earn' => 0];
                    if (isset($value['user']['id'])) {
                        // 精灵收益
                        $value['sprite'] = UserSprite::getInfo($value['user']['id']);
                    }

                    // 排序
                    if ($value['off']) {
                        $sort[$key] = -1 / $value['sprite']['earn'];
                    } else {
                        $sort[$key] = $value['sprite']['earn'];
                    }
                }

                array_multisort($sort, SORT_DESC, $res);
                $res = array_slice($res, ($page - 1) * $size, $size);
            }
        } else if ($type == 2) {
            $res = self::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1, 2]]])->page($page, $size)->select();

            // 师徒页 统计用户今日贡献
            if ($res) {
                foreach ($res as $key => &$value) {
                    $value['user_star'] = $value['user']['user_star'];
                    $value['user_earn'] = ceil(Cfg::getCfg('father_earn_per') * $value['user_star']['thisday_count']);
                    // 排序
                    $sort[$key] = $value['user_star']['thisday_count'];
                }
                array_multisort($sort, SORT_DESC, $res);
            }
        } else {
            // 拉票列表页
            if ($page > 30) return [];
            $res = self::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1, 2]]])->page($page, $size)->select();
            $len = count($res);
            if ($len < $size) {
                for ($i = 0; $i < ($size - $len); $i++) {
                    $res[] = [
                        'status' => 0,
                    ];
                }
            }
        }

        return $res;
    }

    /**手动加好友 */
    public static function addFriend($self, $other)
    {
        if ($self == $other) Common::res(['code' => 100]);
        // 好友数量上限
        $selfFriendCount = self::where('rer_user_id', $self)->count('id');
        if (Cfg::getCfg('friend_max') <= $selfFriendCount) Common::res(['code' => 1, 'msg' => '你已经有足够多的好友了']);

        $isExist = self::where(['rer_user_id' => $self, 'ral_user_id' => $other])->find();
        if (!$isExist) $isExist = self::where(['rer_user_id' => $other, 'ral_user_id' => $self])->find();
        if ($isExist) {
            Common::res(['code' => 1, 'msg' => 'TA已经是你的好友了']);
        } else {
            self::create([
                'rer_user_id' => $self,
                'ral_user_id' => $other,
                'status' => 4
            ]);
        }
    }

    /**删除好友 */
    public static function delFriend($self, $other)
    {
        $isDone = self::where(['rer_user_id' => $self, 'ral_user_id' => $other])->delete(true);
        if (!$isDone) $isDone = self::where(['rer_user_id' => $other, 'ral_user_id' => $self])->delete(true);

        if (!$isDone) {
            Common::res(['code' => 1, 'msg' => '删除失败']);
        }
    }
}
