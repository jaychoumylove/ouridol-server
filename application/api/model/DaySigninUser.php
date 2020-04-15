<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;

class DaySigninUser extends Base
{
    public static function newSignin($uid, $starid, $time)
    {
        self::create([
            'user_id'=>$uid,
            'star_id' => $starid,
            'signin_time' => $time,
        ]);

    }

}