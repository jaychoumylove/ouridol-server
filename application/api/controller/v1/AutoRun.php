<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank;
use think\Db;
use app\api\model\StarRankHistory;
use app\api\model\UserStar;
use app\api\model\UserCurrency;
use app\api\model\OtherLock;

class AutoRun extends Base
{
    public function index()
    { }

    /**每日执行 */
    public function dayHandle()
    {
        Db::startTrans();
        try {
            // 用户日贡献清零
            UserStar::where('1=1')->update([
                'thisday_count' => 0,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            die('rollBack');
        }

        die('done');
    }

    /**每周执行 */
    public function weekHandle()
    {
        if (date("w") != 1) {
            die('today is not monday!');
        }

        $opTime = OtherLock::where('1=1')->max('update_time');
        if (date('oW', time()) == date('oW', strtotime($opTime))) {
            die('this week has executed the method!');
        }
        // lock
        OtherLock::where('1=1')->update(['islock' => 1]);

        Db::startTrans();
        try {
            // 用户能量每周清零
            UserCurrency::where('1=1')->update(['coin' => 0]);

            // 用户周贡献清零
            UserStar::where('1=1')->update([
                'lastweek_count' => Db::raw('thisweek_count'),
                'thisweek_count' => 0,
            ]);

            // 转存历史排名
            $rankList = StarRank::getRankList(1, 10, 'week_hot', '', 0);
            StarRankHistory::create([
                'date' => date('oW', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'week_hot',
            ]);

            // 重置明星人气
            StarRank::where('1=1')->update([
                'week_hot' => 10000,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();

            die('rollBack');
        }

        OtherLock::where('1=1')->update(['islock' => 0]);


        die('done');
    }
}
