<?php

namespace app\api\controller\v1;

use app\api\model\CfgActive;
use app\api\model\UserFather;
use app\api\model\UserRelation;
use app\base\controller\Base;
use app\api\model\StarRank;
use think\Db;
use app\api\model\StarRankHistory;
use app\api\model\UserStar;
use app\api\model\UserCurrency;
use app\api\model\UserSprite;
use app\api\model\Fanclub;
use app\api\model\Star;
use app\base\service\WxAPI;
use app\api\model\Lock;
use app\api\model\Open;
use app\api\model\Prop;
use app\api\model\Rec;
use app\api\model\RecTask;
use app\api\model\UserExt;
use app\api\model\Wxgroup;

class AutoRun extends Base
{
    public function index()
    {
        echo $this->monthHandle() . '</br>';
        echo $this->weekHandle() . '</br>';
        echo $this->dayHandle() . '</br>';
    }

    /**每日执行 */
    public function dayHandle()
    {
        $lock = Lock::getVal('day_end');
        if (date('md', time()) == date('md', strtotime($lock['time']))) {
            return '本日已执行过';
        }
        // lock
        Lock::setVal('day_end', 1);

        Db::startTrans();
        try {
            // 用户日贡献清零
            UserStar::where('1=1')->update([
                'thisday_count' => 0,
                'update_time' => Db::raw('update_time'),
            ]);
            // 道具重置100库存
            Prop::where('1=1')->update([
                'remain' => 100
            ]);
            // 1每日参与群集结次数重置,2剩余开启宝箱次数刷新,3帮助开启宝箱次数,4自动偷心关闭,5、每日电量清零，6、每日师徒收益清零
            UserExt::where('1=1')->update([
                'group_mass_times' => 0,
                'treasure_box_times' => 5,
                'help_open_times' => 0,
                'get_new_invite_energy' => 0,
                'get_old_invite_energy' => 0,
                'invite_energy' => 0,
                'lastday_father_get_count' => Db::raw('father_get_count'),
                'father_get_count' => 0,
            ]);
            // 用户每日产量排行清空,膜拜和被膜拜次数重置
            UserSprite::where('1=1')->update([
                'lastday_coin' => Db::raw('thisday_coin'),
                'thisday_coin' => 0,
                'god_count' => 3,
                'cover_god_count' => 0,
            ]);
            // 群贡献
            Wxgroup::dayInit();

            UserFather::where('1=1')->update([
                'cur_contribute' => 0,
                'has_earn_count' => 0
            ]);

            // 开屏结算
            Open::settle();

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }

        // lock
        Lock::setVal('day_end', 0);

        return '本日执行完毕';
    }

    /**每周执行 */
    public function weekHandle()
    {
        $lock = Lock::getVal('week_end');

        if (date('oW', time()) == date('oW', strtotime($lock['time']))) {
            return '本周已执行过';
        }

        // lock
        Lock::setVal('week_end', 1);

        Db::startTrans();
        try {
            // 用户能量每周清零
            UserCurrency::where('1=1')->update([
                'coin' => 0,
                'update_time' => Db::raw('update_time'),
            ]);

            // 用户周贡献清零
            UserStar::where('1=1')->update([
                'lastweek_count' => Db::raw('thisweek_count'),
                'thisweek_count' => 0,
                'update_time' => Db::raw('update_time'),
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
            $topThreeAward = [49990000, 19990000, 9990000];
            $topThreeIds = array_slice(array_column($rankList, 'star_id'), 0, 3);
            foreach ($topThreeAward as $key => $value) {
                StarRank::where(['star_id' => $topThreeIds[$key]])->update([
                    'week_hot' => Db::raw('week_hot+' . $value),
                    'month_hot' => Db::raw('month_hot+' . $value),
                ]);
            }

            // 后援会贡献重置
            Fanclub::where('1=1')->update(['week_count' => 0]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();

            return 'rollBack:' . $e->getMessage();
        }
        // 解锁
        Lock::setVal('week_end', 0);

        return '本周执行完毕';
    }

    /**每月执行 */
    public function monthHandle()
    {
        $lock = Lock::getVal('month_end');
        if (date('Ym', time()) == date('Ym', strtotime($lock['time']))) {
            return '本月已执行过';
        }

        Lock::setVal('month_end', 1);

        Db::startTrans();
        try {

            // 应援打卡开启，上个月打卡日志保留一个月
            $preShowId = Db::name('cfg_active')->where('delete_time','NOT NULL')->column('id');
            CfgActive::destroy(function ($query){
                $query -> where('1=1');
            });
            $active_date = json_encode([date('Y-m-d'),date('Y-m-d',strtotime("+1 month"))]);
            Db::name('cfg_active')->whereIn('id',$preShowId)->update(['active_date'=>$active_date,'delete_time'=>NULL]);
            Db::name('user_active')->whereIn('active_id',$preShowId)->delete();


            // 用户月贡献清零
            UserStar::where('1=1')->update([
                'lastmonth_count' => Db::raw('thismonth_count'),
                'thismonth_count' => 0,
                'update_time' => Db::raw('update_time'),
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


            // 后援会贡献重置
            Fanclub::where('1=1')->update(['month_count' => 0]);

            //月清好友亲密度
            UserRelation::where('1=1')->update(['intimacy' => 0]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();

            return 'rollBack:' . $e->getMessage();
        }

        Lock::setVal('month_end', 0);

        return '本月执行完毕';
    }

    /**每分钟执行 */
    public function minuteHandle()
    {
        $lock = Lock::getVal('minute_end');

        if (time()-60 < strtotime($lock['time'])) {
            return '本分钟已执行过';
        }

        // lock
        Lock::setVal('minute_end', 1);

        Db::startTrans();
        try {
            //当道具不够的时候恢复
            Prop::where('remain','<',10)->update(['remain'=>99]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();

            return 'rollBack:' . $e->getMessage();
        }

        Lock::setVal('month_end', 0);

        return '本分钟执行完毕';
    }

    /**解锁消息推送 */
    public function sendTmp()
    {
        $starid = input('starid');
        $fee = input('fee');
        $template_id = "T54MtDdRAPe8kNNtt2tQlj7P7ut7yEe-F8-CaMrKcvw";

        $starname = Star::where('id', $starid)->value('name');
        $pushUser = Db::query("SELECT u.openid,f.form_id
                        FROM `f_user_star` as s 
                        join f_user as u on u.id = s.user_id 
                        join   
                            (
                        select * from f_rec_user_formid ORDER BY create_time desc
                        )
                        as f on f.user_id = s.user_id
                        
                        where s.star_id = " . $starid . " GROUP BY u.openid");

        foreach ($pushUser as $value) {
            if (!$value['openid'] || !$value['form_id']) continue;
            $pushDatas[] = [
                "touser" => $value['openid'],
                "template_id" => $template_id,
                "page" => "/pages/index/index",
                "form_id" => $value['form_id'],
                "data" => [
                    "keyword1" => [
                        "value" => $fee . "元"
                    ],
                    "keyword2" => [
                        "value" =>  $starname . "已成功解锁" . $fee . "元应援金，赶快邀请后援会入驻领取吧，活动进行中，最多可解锁1000元。"
                    ]
                ],
                "emphasis_keyword" => "keyword1.DATA"
            ];
        }

        $wxApi = new WxAPI();
        $wxApi->sendTemplate($pushDatas);
    }

    public function clearDb()
    {
        $day = input('day', 10);
        $count = input('count', 100);

        Rec::clear($day, $count);
        RecTask::clear($day, $count);
        echo 'done';
    }
}
