<?php

namespace app\api\model;

use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class HongbaoUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**打开宝箱 */
    public static function openBox($uid, $box_id)
    {
        $isExist = (new HongbaoUser)->readMaster()->where('box_id', $box_id)->where('user_id', $uid)->value('id');
        if ($isExist) return;
        
        //不在一个圈子
        $sendUserId = Hongbao::where('id', $box_id)->value('user_id');
        $sendStarId = UserStar::where('user_id',$sendUserId)->value('star_id');
        $getStarId = UserStar::where('user_id',$uid)->value('star_id');
        if ($sendStarId!=$getStarId)  Common::res(['code' => 1, 'msg' => '不在同一个偶像圈']);

        // 宝箱信息
        $boxInfo = Hongbao::where('id', $box_id)->find();

        // 红包总个数
        $totalCount = $boxInfo['people'];
        $count = self::where('box_id', $box_id)->count('id');
        // 剩余个数
        $remainCount = $totalCount - $count;
        if ($remainCount <= 0) return;

        // 总奖金
        $totalSum = $boxInfo['coin'];
        $sum = self::where('box_id', $box_id)->sum('count');
        // 剩余奖金
        $remainSum = $totalSum - $sum;
        if ($remainSum <= 0) return;
        // 给予奖励数额
        $awardNum = self::getAward($remainSum, $remainCount);

        Db::startTrans();
        try {
            self::create([
                'box_id' => $box_id,
                'user_id' => $uid,
                'count' => $awardNum
            ]);

            (new UserService)->change($uid, ['coin' => $awardNum],  ['type' => 33]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return true;
    }

    /**
     * 获取一个红包的奖金金额
     * @param int $total 奖金
     * @param int $count 剩余个数
     */
    public static function getAward($total, $count)
    {
        if ($count == 1) {
            // 最后一个红包，奖金全部给TA
            $award = $total;
        } else {
            // 奖金额度 = 奖金 / 红包个数 * 随机0.50-1.49倍
            do {
                $award = round($total / $count * mt_rand(50, 149) / 100);
            } while ($award >= $total);
        }

        return $award;
    }

    public static function getDouble($uid)
    {
        $hongbaoUser = (new HongbaoUser)->readMaster()->where('user_id', $uid)->order('id desc')->find();

        if ($hongbaoUser['double'] == 0) {
            Db::startTrans();
            try {
                (new UserService)->change($uid, ['coin' => $hongbaoUser['count']], ['type' => 34]);

                self::where('id', $hongbaoUser['id'])->update(['double' => 1]);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }
    }
}
