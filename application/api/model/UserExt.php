<?php

namespace app\api\model;

use app\base\model\Base;

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
     * 寻宝剩余时间
     */
    public static function getTreasure($uid)
    {
        $treasureTime = Self::get(['user_id' => $uid])['treasure_time'];
        $limitTime = Cfg::getCfg('treasure_time');

        $time = $limitTime - (time() - $treasureTime);
        if ($time < 0) {
            $time = 0;
        }
        return $time;
    }
}
