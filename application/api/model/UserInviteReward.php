<?php

namespace app\api\model;

use app\base\model\Base;

class UserInviteReward extends Base
{

    public static function inviteRewardLog($uid, $page, $size)
    {
        $logList = self::where('user_id',$uid)->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

        foreach ($logList as &$value) {
            $value['content'] = '领取积攒电量奖励';
            $value['reward'] = json_decode($value['reward'], true);
            $value['reward']['prop_img'] = Prop::where('id',$value['reward']['prop'])->value('img');
        }
        return $logList;
    }
}
