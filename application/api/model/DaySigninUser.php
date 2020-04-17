<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;

class DaySigninUser extends Base
{
    public static function newSignin($uid, $star_id, $time)
    {
        $isRec=self::where(['user_id'=>$uid])->update(['signin_time'=>$time]);
        if(!$isRec){
            self::create([
                'user_id'=>$uid,
                'star_id' => $star_id,
                'signin_time' => $time,
            ]);
        }


    }

}