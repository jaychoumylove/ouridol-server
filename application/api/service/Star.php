<?php

namespace app\api\service;

use app\api\model\StarRank as StarRankModel;
use think\Db;
use app\api\model\UserStar;
use app\api\service\User as UserService;
use app\base\service\Common;
use app\api\model\Rec;
use app\api\model\Cfg;
use app\api\model\UserRelation;
use app\api\model\UserFather;
use app\api\model\OtherLock;
use think\Cache;
use app\api\model\UserExt;
use app\api\model\CfgItem;
use app\api\model\UserItem;
use app\api\model\User as UserModel;
use GatewayWorker\Lib\Gateway;
use app\api\model\RecItem;
use app\api\model\Fanclub;

class Star
{

    public function getRank($score, $field)
    {
        return StarRankModel::where($field, 'GT', $score)->count() + 1;
    }

    /**打榜 */
    public function sendHot($starid, $hot, $uid, $type)
    {
        if (Cache::get('lockSend')['isLock'] == 1) {
            Common::res(['code' => 1, 'msg' => '榜单结算中，请稍后再试！']);
        }

        Db::startTrans();
        try {
            if ($type == 1) {
                // 送礼物
                $item_id = $hot;
                $hot = CfgItem::where(['id' => $item_id])->value('count');

                // 礼物减少
                $count = UserItem::where(['uid' => $uid, 'item_id' => $item_id])->value('count');
                if (!$count || $count <= 0) Common::res(['msg' => '礼物不足', 'data' => ['nomore' => true]]);

                UserItem::where(['uid' => $uid, 'item_id' => $item_id])->update([
                    'count' => Db::raw('count-1')
                ]);

                $itemInfo = CfgItem::where(['id' => $item_id])->field('name,icon,count')->find();
                // 送礼物记录
                RecItem::create([
                    'user_id' => $uid,
                    'item_id' => $item_id,
                    'star_id' => $starid,
                    'valueof' => $itemInfo['count']
                ]);

                // 推送
                $userInfo = UserModel::where(['id' => $uid])->field('nickname,avatarurl')->find();
                Gateway::sendToGroup('star_' . $starid, json_encode([
                    'type' => 'sendItem',
                    'data' => [
                        'itemicon' => $itemInfo['icon'],
                        'itemname' => $itemInfo['name'],
                        'username' => $userInfo['nickname'],
                        'avatar' => $userInfo['avatarurl']
                    ]
                ], JSON_UNESCAPED_UNICODE));

                // 日志
                Rec::create(['user_id' => $uid, 'content' => json_encode([$itemInfo['name']], JSON_UNESCAPED_UNICODE), 'type' => 15]);
            } else if ($type == 0) {
                // 送能量
                // 用户货币减少
                (new UserService())->change($uid, [
                    'coin' => $hot / -1,
                ], [
                    'type' => 2,
                    'target_star_id' => $starid,
                ]);
            }

            // 用户贡献度增加
            UserStar::change($uid, $starid, $hot);
            // 后援会贡献度增加
            Fanclub::change($uid, $hot);

            // 徒弟贡献 
            $opTime = UserFather::where(['son' => $uid])->value('update_time');
            if ($opTime) {
                if (date('Ymd', strtotime($opTime)) != date('Ymd', time())) {
                    UserFather::where(['son' => $uid])->update([
                        'cur_contribute' => $hot,
                    ]);
                } else {
                    UserFather::where(['son' => $uid])->update([
                        'cur_contribute' => Db::raw('cur_contribute+' . $hot)
                    ]);
                }
            }

            // 明星增加人气
            StarRankModel::where(['star_id' => $starid])->update([
                'week_hot' => Db::raw('week_hot+' . $hot),
                'month_hot' => Db::raw('month_hot+' . $hot),
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }

    /**偷能量 */
    public function steal($starid, $uid, $hot)
    {
        UserExt::checkSteal($uid);
        $userExt = UserExt::where(['user_id' => $uid])->field('steal_times,steal_count')->find();
        if ($userExt['steal_times'] >= Cfg::getCfg('steal_limit')) {
            Common::res(['code' => 1, 'msg' => '今日偷取次数已达上限']);
        }

        if ($userExt['steal_count'] >= Cfg::getCfg('steal_count_limit')) {
            Common::res(['code' => 1, 'msg' => '今日偷取数额已达上限']);
        }

        Db::startTrans();
        try {
            StarRankModel::where(['star_id' => $starid])->update([
                'week_hot' => Db::raw('week_hot-' . $hot),
                'month_hot' => Db::raw('month_hot-' . $hot),
            ]);

            (new UserService())->change($uid, [
                'coin' => $hot,
            ]);

            UserExt::where(['user_id' => $uid])->update([
                'steal_times' => Db::raw('steal_times+1'),
                'steal_count' => Db::raw('steal_count+' . $hot),
                'steal_time' => time(),
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }
}
