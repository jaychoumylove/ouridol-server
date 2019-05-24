<?php

namespace app\api\model;

use app\base\model\Base;

class RecStarChart extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    public static function getLeastChart($starid)
    {
        $list = self::with(['User' => ['UserStar']])->where(['star_id' => $starid])->order('id desc')->limit(10)->select();


        return array_reverse($list);
    }
}
