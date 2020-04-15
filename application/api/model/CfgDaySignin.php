<?php


namespace app\api\model;


use app\base\model\Base;

class CfgDaySignin extends Base
{
    public static function status()
    {
        $pkTime = self::all();
        $data['pkTimeList'] = $pkTime;

        foreach ($pkTime as $value) {
            $startTime = strtotime($value['start_time'] . ':00');
            $endTime = strtotime($value['end_time'] . ':00');

            if (time() >= $startTime && time() <= $endTime) {
                // 签到时间
                $data['status'] = 2;
                // 时间点信息
                $data['curPkTime'] = $value;
                // 签到剩余时间
                $data['timeLeft'] = $endTime - time();
                //签到奖励
                $data['coin']=$value['coin'];
                break;
            }
        }
        $data['whole_time'] = date('Y-m-d', time()) . ' ' . $data['curPkTime']['start_time'] . ':00';

        return $data;
    }

}