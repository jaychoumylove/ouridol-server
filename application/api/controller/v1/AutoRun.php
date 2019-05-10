<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank;
use think\Db;
use app\api\model\StarRankHistory;
use app\api\model\UserStar;

class AutoRun extends Base
{
    public function index()
    { }

    /**每日执行 */
    public function dayHandle()
    {
        Db::startTrans();
        try {
            // 用户贡献清零
            UserStar::where(false)->update([
                'thisday_count' => 0,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
        }
    }

    /**每周执行 */
    public function weekHandle()
    {
        Db::startTrans();
        try {
            // 用户贡献清零
            UserStar::where(false)->update([
                'thisweek_count' => 0,
            ]);

            // 转存历史排名
            $topThree = StarRank::getRankList(1, 3, 'week_hot', '', 0);
            StarRankHistory::create([
                'date' => date('oW', time() - 3600),
                'value' => json_encode($topThree, JSON_UNESCAPED_UNICODE),
            ]);

            // 重置明星人气
            StarRank::where(false)->update([
                'week_hot' => 10000,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
        }
    }
}
