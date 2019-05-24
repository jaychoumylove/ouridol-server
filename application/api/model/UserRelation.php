<?php

namespace app\api\model;

use app\base\model\Base;

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

    public static function fixByType($type, $uid, $page, $size)
    {
        if ($type == 1) { // 宠物页面邀请列表，统计收益
            // 我邀请的人
            $res = UserRelation::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1, 2, 3]]])->page($page, $size)->select();
            if ($page == 1) {
                // 邀请我的人
                $ralUser = self::with('RerUser')->where(['ral_user_id' => $uid, 'status' => ['in', [1, 2]]])->find();
                if ($ralUser) {
                    $ralUser['user'] = $ralUser['rer_user'];
                    $ralUser = [$ralUser];
                } else {
                    $ralUser = [];
                }

                $res = array_merge($res, $ralUser);
            }

            if ($res) {
                foreach ($res as $key => &$value) {

                    $value['sprite'] = ['earn' => 0];
                    if (isset($value['user']['id'])) {
                        // 精灵收益
                        $value['sprite'] = UserSprite::getInfo($value['user']['id']);
                    }

                    // 排序
                    $sort[$key] = $value['sprite']['earn'];
                }

                array_multisort($sort, SORT_DESC, $res);
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
            $res = self::with('User')->where(['rer_user_id' => $uid, 'status' => ['in', [1, 2]]])->page($page, $size)->select();
            if (count($res) < $size && $page <= 10) {
                for ($i = 0; $i < ($size - count($res)); $i++) {
                    $res[] = [
                        'status' => 0,
                        'user' => [
                            'avatarurl' => 'https://wx.qlogo.cn/mmhead/gBSelbQM7M19TeazvLwo3f8znKS8KR1CuibicFHc1GTWI/132',
                        ]
                    ];
                }
            }
        }

        return $res;
    }
}
