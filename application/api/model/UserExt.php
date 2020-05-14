<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;

class UserExt extends Base
{
    public static function setTime($uid, $index)
    {
        $item = self::get(['user_id' => $uid]);

        $leftTime = json_decode($item['left_time'], true);
        $leftTime[$index] = time();
        $leftTime = json_encode($leftTime);

        self::where(['user_id' => $uid])->update([
            'left_time' => $leftTime
        ]);
    }

    /**
     * 抽奖
     * 
     * 抽一次奖扣1能量
     * 每小时回复10点能量
     * 上限100能量
     */
    public static function getTreasure($uid)
    {
        self::addCount($uid);

        $ext = self::where('user_id', $uid)->field('lottery_count,lottery_time')->find();
        if ($ext['lottery_count'] < Cfg::getCfg('lottery')['max']) {
            $ext['remain_time'] = Cfg::getCfg('lottery')['per'] - (time() - $ext['lottery_time']);
        }
        return $ext;
    }

    /**增加能量点数 */
    public static function addCount($uid)
    {
        $ext = self::where('user_id', $uid)->field('lottery_count,lottery_time')->find();

        // 每1小时，增加10点能量
        $addCount = floor((time() - $ext['lottery_time']) / Cfg::getCfg('lottery')['per']) * Cfg::getCfg('lottery')['reply'];

        if ($addCount) {
            $count = $ext['lottery_count'] + $addCount;

            if ($count > Cfg::getCfg('lottery')['max']) $count = Cfg::getCfg('lottery')['max'];

            self::where('user_id', $uid)->update([
                'lottery_count' => $count,
                'lottery_time' => time(),
            ]);
        }
    }

    /**抽奖 */
    public static function lotteryStart($uid)
    {
        self::addCount($uid);

        $ext = self::where('user_id', $uid)->field('lottery_count,lottery_time')->find();
        if ($ext['lottery_count'] <= 0) Common::res(['code' => 1, 'msg' => '没有能量了']);

        // 随机一个奖品
        $lotteryList = CfgLottery::where('1=1')->order('chance asc')->select();
        $totalPt = CfgLottery::where('1=1')->sum('chance');
        $randPt = mt_rand(0, $totalPt);
        $basePt = 0;
        foreach ($lotteryList as $value) {
            if ($randPt <= $value['chance'] + $basePt) {
                $lottery = $value;
                break;
            } else {
                $basePt += $value['chance'];
            }
        }
        // 扣除能量
        self::where('user_id', $uid)->update([
            'lottery_count' => Db::raw('lottery_count-1')
        ]);
        self::change($uid, $lottery);

        return $lottery;
    }

    /**增加用户货币 */
    public static function change($uid, $lottery)
    {

        if ($lottery['type'] == 1) $type = 'coin';
        else if ($lottery['type'] == 2) $type = 'stone';
        else if ($lottery['type'] == 3) $type = 'trumpet';

        (new User())->change($uid, [
            $type => $lottery['num']
        ], [
            'type' => 24
        ]);
    }

    /**偷取次数清零 */
    public static function checkSteal($uid)
    {
        $stealTime = self::where(['user_id' => $uid])->value('steal_time');
        if (date('Ymd', $stealTime) != date('Ymd', time())) {
            // 偷取次数清零
            self::where(['user_id' => $uid])->update([
                'steal_times' => 0,
                'steal_count' => 0,
            ]);
        }
    }

    /**
     * 补偿 
     */
    public static function redress($uid)
    {
        $redressDate = Cfg::getCfg('redress_date');
        $redressTime = UserExt::where('user_id', $uid)->value('redress_time');
        
        if ($redressTime > strtotime($redressDate[0]) && $redressTime < strtotime($redressDate[1])) {
            return [
                'status' => 1,
                'msg' => '你已领取过补偿'
            ];
        }
        if (time() < strtotime($redressDate[0])) {
            return [
                'status' => 1,
                'msg' => '补偿未到时间'
            ];
        }
        if (time() > strtotime($redressDate[1])) {
            return [
                'status' => 1,
                'msg' => '补偿已过期'
            ];
        }

        $msg = '领取成功';
        $update['coin'] = 100000;
        $msg .= '，能量+' . $update['coin'];
        $update['stone'] = 30;
        $msg .= '，灵丹+' . $update['stone'];

        (new User)->change($uid, $update, ['type' => 36]);

        UserExt::where('user_id', $uid)->update(['redress_time' => time()]);
        return [
            'status' => 0,
            'msg' => $msg
        ];
    }
    
    /**
     * 公众号表白
     */
    public static function biaobai($user_id,$star_id,$star_name)
    {
        $isSignin = self::where('user_id', $user_id)->whereTime('gzh_signin_time', 'd')->value('id');

        if ($isSignin) {
            $msg = "你今天已经表白了哦，请明日再来!";
            
        } else {
            try {

                $isDone = GzhBiaobai::where('star_id', $star_id)->update([
                    'ticket' => Db::raw('ticket+1')
                ]);
                
                if(!$isDone) GzhBiaobai::create([
                    'star_id'=>$star_id,
                ]);
                
                self::where('user_id', $user_id)->update(['gzh_signin_time'=>time()]);                
                Db::commit();
                
            } catch (\Exception $e) {
                Db::rollBack();    
                $msg = "rollBack: ". $e->getMessage();
            }

            $tickets = GzhBiaobai::where('star_id',$star_id)->value('ticket')+1;
            $star_rank = GzhBiaobai::where('ticket', 'GT', $tickets)->count() + 1;
            $next_star_rank = $star_rank + 1;
            
            $msg = "恭喜，已经成功向{$star_name}表白。";
            $msg .= "\n{$star_name}目前{$tickets}票排名第{$star_rank}，第{$next_star_rank}名就要追上来啦。";
            $msg .= "\n你还可以分享链接邀请你的小伙伴一起来向{$star_name}表白，还有更多福利等着你。";
        }

        return $msg;
    }
}
