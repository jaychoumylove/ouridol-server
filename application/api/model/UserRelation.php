<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

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
            // 新用户打卡活动解锁10次
            $rerType = User::where('id', $relation['rer_user_id'])->value('type');

            if ($rerType == 0) {
                UserStar::where('user_id', $relation['rer_user_id'])->update([
                    'active_card_days' => Db::raw('active_card_days+10'),
                    'active_newbie_cards' => Db::raw('active_newbie_cards+10'),
                ]);
                // 判断是否结成师徒关系
                UserFather::join($relation['rer_user_id'], $uid);
            }
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
            $friend_max = Cfg::getCfg('friend_max');
            if (!$friend_max) $friend_max = 100;
            // 我邀请的人、、加的好友
            $res = UserRelation::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1, 2, 3, 4]]])->order('create_time desc')->limit($friend_max)->select();
            if (count($res) < $friend_max) {
                // 邀请我的人、手动被加的好友
                $ralUser = self::with('RerUser')->where(['ral_user_id' => $uid, 'status' => ['in', [1, 2, 4]]])->limit($friend_max - count($res))->select();
                foreach ($ralUser as &$value) {
                    $value['user'] = $value['rer_user'];
                }
                $res = array_merge($res, $ralUser);
            }
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
                    if ($value['off'] && $value['sprite']['earn']) {
                        $sort[$key] = -1 / $value['sprite']['earn'];
                    } else {
                        $sort[$key] = $value['sprite']['earn'];
                    }
                }

                array_multisort($sort, SORT_DESC, $res);
                $data['total_count'] = count($res);
                $data['list'] = array_slice($res, ($page - 1) * $size, $size);
                $res = $data;
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
        } else if ($type == 3) {
            // 打卡解锁活动拉新
            $res = self::with('User')->where(['rer_user_id' => $uid, 'create_time' => ['>', date('Y-m-d H:i:s', Cfg::getCfg('active_date')[0])], 'status' => ['in', [1, 2]]])->order('id desc')->limit(5)->select();
        } else {
            // 拉票列表页
            // 已领取人数
            $res['hasEarnCount'] = self::where(['rer_user_id' => $uid, 'status' => ['in', [2]]])->count('id');
            if ($res['hasEarnCount'] <= 300) {
                $res['list'] = self::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1]]])->page($page, $size)->select();
                $len = count($res['list']);
                if ($len < $size) {
                    for ($i = 0; $i < ($size - $len); $i++) {
                        $res['list'][] = [
                            'status' => 0,
                        ];
                    }
                }
            } else {
                $res['list'] = [];
            }
        }

        return $res;
    }

    /**手动加好友 */
    public static function addFriend($self, $other)
    {
        if (!$self || !$other || $self == $other) Common::res(['code' => 100]);
        // 好友数量上限
        $selfFriendCount = self::where('rer_user_id', $self)->where('status', 'in', [1, 2, 3, 4])->count('id');
        $selfFriendCount += self::where(['ral_user_id' => $self, 'status' => ['in', [1, 2, 4]]])->count('id');
        if (Cfg::getCfg('friend_max') <= $selfFriendCount) Common::res(['code' => 1, 'msg' => '你已经有足够多的好友了']);

        $otherFriendCount = self::where('rer_user_id', $other)->where('status', 'in', [1, 2, 3, 4])->count('id');
        $otherFriendCount += self::where(['ral_user_id' => $other, 'status' => ['in', [1, 2, 4]]])->count('id');
        if (Cfg::getCfg('friend_max') <= $otherFriendCount) Common::res(['code' => 1, 'msg' => 'TA的好友已满']);

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
