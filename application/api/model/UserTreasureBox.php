<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class UserTreasureBox extends Base
{
    public static function getList($uid, $page, $size)
    {
        $logList = self::where(['user_id' => $uid])->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

//        foreach ($logList as &$value) {
//
//        }
        return $logList;
    }

    public static function checkTime()
    {

        $time1 = date('Y-m-d') . ' 00:00:00';
        $time2 = date('Y-m-d') . ' 11:00:00';
        $time3 = date('Y-m-d') . ' 18:00:00';
        if (date('Y-m-d H:i:s') > $time1 && date('Y-m-d H:i:s') < $time2) {
            $res['date'] = $time1;
            $res['nextTime'] = strtotime($time2) - time();
            $res['nextTimeText'] = '11:00';
        } elseif (date('Y-m-d H:i:s') > $time2 && date('Y-m-d H:i:s') < $time3) {
            $res['date'] = $time2;
            $res['nextTime'] = strtotime($time3) - time();
            $res['nextTimeText'] = '18:00';
        } else {
            $res['date'] = $time3;
            $res['nextTime'] = strtotime(date('Y-m-d', strtotime('+1 day'))) - time();
            $res['nextTimeText'] = '00:00';
        }
        return $res;
    }

    public static function openBox($uid,$self,$index){

    }



}
