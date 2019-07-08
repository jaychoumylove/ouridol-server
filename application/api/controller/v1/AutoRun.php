<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank;
use think\Db;
use app\api\model\StarRankHistory;
use app\api\model\UserStar;
use app\api\model\UserCurrency;
use app\api\model\OtherLock;
use think\Cache;
use app\api\model\Fanclub;

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
            die('rollBack:' . $e->getMessage());
        }

        die('done');
    }

    /**每周执行 */
    public function weekHandle()
    {
        // if (date("w") != 1) {
        //     die('今日不是星期一');
        // }

        $opTime = Cache::get('lockSend')['time'];
        if (date('oW', time()) == date('oW', $opTime)) {
            die('本周已执行过');
        }

        // lock
        Cache::set('lockSend', [
            'isLock' => 1,
            'time' => time()
        ]);

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

            // 前三
            $topThreeAward = [290000, 190000, 90000];
            $topThreeIds = array_slice(array_column($rankList, 'star_id'), 0, 3);
            foreach ($topThreeAward as $key => $value) {
                StarRank::where(['star_id' => $topThreeIds[$key]])->update([
                    'week_hot' => Db::raw('week_hot+' . $value),
                    'month_hot' => Db::raw('month_hot+' . $value),
                ]);
            }

            // 后援会
            Fanclub::where('1=1')->update(['week_count'])

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();

            die('rollBack:' . $e->getMessage());
        }

        Cache::set('lockSend', [
            'isLock' => 0,
            'time' => time()
        ]);

        die('done');
    }

    public function monthHander()
    {
        $opTime = Cache::get('monthOptime');
        if (date('Ym', time()) == date('Ym', $opTime)) {
            die('本月已执行过');
        }
        Cache::set('monthOptime',  time());

        Db::startTrans();
        try {
            // 用户月贡献清零
            UserStar::where('1=1')->update([
                'lastmonth_count' => Db::raw('thismonth_count'),
                'thismonth_count' => 0,
            ]);

            // 转存历史排名
            $rankList = StarRank::getRankList(1, 10, 'month_hot', '', 0);

            StarRankHistory::create([
                'date' => date('Ym', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'month_hot',
            ]);

            // 重置明星人气
            StarRank::where('1=1')->update([
                'month_hot' => 10000,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();

            die('rollBack:' . $e->getMessage());
        }

        die('done');
    }
}
