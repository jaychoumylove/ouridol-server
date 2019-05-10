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

class Star
{

    public function getRank($score, $field)
    {
        return StarRankModel::where($field, 'GT', $score)->count() + 1;
    }

    public function sendHot($starid, $hot, $uid)
    {
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

            // 徒弟送能量 师傅获得贡献度30%的能量
            $father = UserFather::where(['son' => $uid])->value('father');
            if ($father) {
                (new User())->change($father, [
                    'coin' => ceil($hot * Cfg::getCfg('father_earn_per')),
                ]);
            }

            // 明星增加人气
            StarRankModel::where(['star_id' => $starid])->update([
                'week_hot' => Db::raw('week_hot+' . $hot),
                'month_hot' =>  Db::raw('month_hot+' . $hot),
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400]);
        }
    }

    public function steal($starid, $uid)
    {
        $hot = Cfg::getCfg('stealCount');
        Db::startTrans();
        try {
            StarRankModel::where(['star_id' => $starid])->update([
                'week_hot' => Db::raw('week_hot-' . $hot),
                // 'month_hot' =>  Db::raw('month_hot-' . $hot),
            ]);

            (new User())->change($uid, [
                'coin' => $hot / 1,
            ], [
                'type' => 1,
                'target_star_id' => $starid,
            ]);


            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400]);
        }
    }
}
