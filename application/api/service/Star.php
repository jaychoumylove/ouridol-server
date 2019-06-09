<?php
namespace app\api\service;

use app\api\model\StarRank as StarRankModel;
use think\Db;
use app\api\model\UserStar;
use app\base\service\Common;
use app\api\model\Rec;
use app\api\model\Cfg;
use app\api\model\UserRelation;
use app\api\model\UserFather;
use app\api\model\OtherLock;
use think\Cache;
use app\api\model\UserExt;

class Star
{

    public function getRank($score, $field)
    {
        return StarRankModel::where($field, 'GT', $score)->count() + 1;
    }

    /**打榜 */
    public function sendHot($starid, $hot, $uid)
    {
        if (Cache::get('lockSend')['isLock'] == 1) {
            Common::res(['code' => 1, 'msg' => '榜单结算中，请稍后再试！']);
        }

        Db::startTrans();
        try {
            // 用户贡献度增加
            UserStar::change($uid, $starid, $hot);

            // 用户货币减少
            (new User())->change($uid, [
                'coin' => $hot / -1,
            ], [
                'type' => 2,
                'target_star_id' => $starid,
            ]);

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

    public function steal($starid, $uid)
    {
        $hot = Cfg::getCfg('stealCount');

        $userExt = UserExt::where(['user_id' => $uid])->field('steal_times,update_time')->find();
        if ($userExt['steal_times'] >= Cfg::getCfg('steal_limit')) {
            Common::res(['code' => 1, 'msg' => '今日次数已达上限']);
        }

        Db::startTrans();
        try {

            StarRankModel::where(['star_id' => $starid])->update([
                'week_hot' => Db::raw('week_hot-' . $hot),
                'month_hot' => Db::raw('month_hot-' . $hot),
            ]);

            (new User())->change($uid, [
                'coin' => $hot,
            ], [
                'type' => 1,
                'target_star_id' => $starid,
            ]);

            if (date('Ymd', strtotime($userExt['update_time'])) != date('Ymd', time())) {
                // 偷取次数清零
                UserExt::where(['user_id' => $uid])->update([
                    'steal_times' => 1
                ]);
            } else {
                UserExt::where(['user_id' => $uid])->update([
                    'steal_times' => Db::raw('steal_times+1')
                ]);
            }


            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }
}
