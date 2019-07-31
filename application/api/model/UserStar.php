<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;
use app\base\service\WxAPI;

class UserStar extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    /**获取用户爱豆id */
    public static function getStarId($uid)
    {
        return self::where('user_id', $uid)->order('id desc')->value('star_id');
    }

    /**用户贡献排名 */
    public static function getRank($starid, $field, $page, $size)
    {
        if ($starid) {
            $w = ['star_id' => $starid];
        } else {
            $w = false;
        }

        return self::with('User')->where($w)->where([$field => ['neq', 0]])->order($field . ' desc')->page($page, $size)->select();
    }

    /**贡献度改变 */
    public static function change($uid, $starid, $hot)
    {
        self::where(['user_id' => $uid, 'star_id' => $starid])->update([
            'total_count' => Db::raw('total_count+' . $hot),
            'thisday_count' => Db::raw('thisday_count+' . $hot),
            'thisweek_count' => Db::raw('thisweek_count+' . $hot),
            'thismonth_count' => Db::raw('thismonth_count+' . $hot),
        ]);
    }

    /**加入爱豆圈子 */
    public static function joinNew($starid, $uid)
    {
        Db::startTrans();
        try {
            $userType = User::where('id', $uid)->value('type');
            if ($userType == 1) {
                // 管理员
                $uid = self::getVirtualUser($starid, $uid);
            }

            if (!self::get(['user_id' => $uid, 'star_id' => $starid])) {
                // Common::res(['code' => 302]);
                self::create([
                    'user_id' => $uid, 'star_id' => $starid
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
        return $uid;
    }

    /**
     * 获取该管理员在该圈子的虚拟用户
     * @param mixed $uid 管理员uid
     * @return mixed 虚拟用户uid
     */
    public static function getVirtualUser($starid, $uid)
    {
        $user = User::where('id', $uid)->find();
        if (strpos($user['openid'], '@') !== false) {
            Common::res(['code' => 1, 'msg' => '请尝试清除缓存后再试']);
        }
        $oldStarid = UserStar::where('user_id', $uid)->value('star_id');
        if (!$oldStarid) $oldStarid = 0;
        // 旧 带上oldStarid后缀
        User::where('id', $uid)->update([
            'openid' => $user['openid'] . '@' . $oldStarid,
            'unionid' => $user['unionid'] . '@' . $oldStarid
        ]);

        // 新的角色
        $virtualUid = User::where(['openid' => $user['openid'] . '@' . $starid])->value('id');
        if (!$virtualUid) {
            $virtualUid = User::createVirtualUser([
                'openid' => $user['openid'],
                'unionid' => $user['unionid'],
            ]);
        } else {
            User::where(['openid' => $user['openid'] . '@' . $starid])->update([
                'openid' => $user['openid'],
                'unionid' => $user['unionid']
            ]);
        }

        return $virtualUid;
    }

    /**退出偶像圈 */
    public static function exit($uid)
    {
        $ext = UserExt::get(['user_id' => $uid]);
        if (time() - $ext['exit_group_time'] > 3600 * 24 * 365 / 2) {
            // 半年才能退一次
            Db::startTrans();
            try {
                // 退圈
                self::destroy(['user_id' => $uid], true);
                // 记录退圈时间
                UserExt::where(['user_id' => $uid])->update(['exit_group_time' => time()]);
                // 清除师徒关系
                UserFather::where(['father' => $uid])->whereOr(['son' => $uid])->delete(true);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400, 'data' => $e->getMessage()]);
            }
        } else {
            Common::res(['code' => 1, 'msg' => '退出偶像圈失败，上次退出偶像圈时间为' . date('Y-m-d', $ext['exit_group_time'])]);
        }
    }

    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $starid, $field)
    {
        $res['score'] = self::where(['user_id' => $uid, 'star_id' => $starid])->value($field);
        if ($res['score']) {
            $res['rank'] = self::where('star_id', $starid)->where($field, '>', $res['score'])->count() + 1;
        } else {
            $res['rank'] = '未上榜';
        }

        return $res;
    }

    /**活动信息 */
    public static function getActiveInfo($uid, $starid)
    {
        // 活动信息
        $res['active_info'] = Cfg::getCfg('active_info');
        // 活动时间
        $activeTime = Cfg::getCfg('active_date');
        $res['active_end'] = $activeTime[1] - time();
        if ($res['active_end'] < 0) $res['active_end'] = 0;
        // 参与人数
        // $res['join_people'] = self::where(['star_id' => $starid])->where('active_card_days', '>', 0)->count();
        // 累计打卡次数
        $res['complete_people'] = self::where(['star_id' => $starid])->sum('active_card_days');
        $res['nextCount'] = '已完成解锁！';
        foreach ($res['active_info'] as $value) {
            if ($res['complete_people'] < $value['count']) {
                // 下一目标次数与金额
                $res['nextCount'] = $value['count'];
                $res['nextFee'] = $value['fee'];
                break;
            } else {
                // 已达成次数与金额
                $res['finishedCount'] = $value['count'];
                $res['finishedFee'] = $value['fee'];
            }
        }
        // 预计每天需要多少人次打卡才能达成下一目标
        if (isset($res['nextCount']) && gettype($res['nextCount']) == 'integer') {
            $res['remainPeople'] = 10; // 初步预计每天10人
            $gapCount = $res['nextCount'] - $res['complete_people'];
            $avgSpriteLv = UserSprite::where('user_id', 'in', self::where('star_id', $starid)->where('active_card_days', '>', 0)->column('user_id'))->avg('sprite_level') * 3;
            if ($avgSpriteLv && $res['active_end']) $res['remainPeople'] = ceil($gapCount / $avgSpriteLv / ($res['active_end'] / 3600 / 24));
        } else {
            $res['remainPeople'] = 0;
        }

        $active_card = self::where(['user_id' => $uid, 'star_id' => $starid])->field('active_card_days,active_card_time,active_subscribe,active_newbie_cards')->find();
        // 今日是否已打卡
        $res['can_card'] = date('ymd', time()) != date('ymd', $active_card['active_card_time']) ? UserSprite::where('user_id', $uid)->value('sprite_level') * 1 : false;
        // 我的累计打卡
        $res['my_card_days'] = $active_card['active_card_days'] ? $active_card['active_card_days'] : 0;
        $res['my_newbie_cards'] = $active_card['active_newbie_cards'] ? $active_card['active_newbie_cards'] : 0;
        // 是否订阅
        $res['active_subscribe'] = $active_card['active_subscribe'];
        // canvas活动标题
        $res['canvas_title'] = Cfg::getCfg('canvas_title_active');
        return $res;
    }

    /**活动 打卡 */
    public static function setCard($uid)
    {
        $activeEnd = Cfg::getCfg('active_date')[1];
        if ($activeEnd - time() < 0) Common::res(['code' => 1, 'msg' => '本次活动已结束']);

        $active_card = self::where(['user_id' => $uid])->field('star_id,active_card_time,active_subscribe')->find();
        if (date('ymd', time()) == date('ymd', $active_card['active_card_time'])) {
            Common::res(['code' => 1, 'msg' => '你今天已经打卡了哦']);
        }

        Db::startTrans();
        try {
            $res = [];

            // 打卡数额由用户精灵等级决定
            $count = UserSprite::where('user_id', $uid)->value('sprite_level') * 1;

            // 推送解锁进度
            // self::push($active_card['star_id'], $count);

            // 是否订阅
            $active_subscribe = $active_card['active_subscribe'];
            if ($active_subscribe == 0) {
                $active_subscribe = 2;
                $res['subscribe'] = true;
            }

            self::where(['user_id' => $uid])->update([
                'active_card_days' => Db::raw('active_card_days+' . $count),
                'active_card_time' => time(),
                'active_subscribe' => $active_subscribe
            ]);

            // 记录
            RecActive::create(['user_id' => $uid, 'card_count' => $count, 'card_time' => (int) date('Ymd')]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }


        return $res;
    }

    /**推送解锁 */
    public static function push($starid, $count)
    {
        $activeInfo = Cfg::getCfg('active_info');
        // 当前打卡数
        $beforeCards = self::where('star_id', $starid)->sum('active_card_days');
        // 之后打卡数
        $afterCards = $beforeCards + $count;
        $beforeFee = 0;
        $afterFee = 0;
        foreach ($activeInfo as $value) {
            if ($beforeCards < $value['count']) {
                break;
            } else {
                $beforeFee = $value['fee'];
            }
        }
        foreach ($activeInfo as $value) {
            if ($afterCards < $value['count']) {
                break;
            } else {
                $afterFee = $value['fee'];
            }
        }
        if ($beforeFee != $afterFee) {
            // 确认推送
            Common::requestAsync('https://' . $_SERVER['HTTP_HOST'] . '/api/v1/auto/sendTmp', http_build_query([
                'starid' => $starid,
                'fee' => $afterFee
            ]));
        }
    }
}
