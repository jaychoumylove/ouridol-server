<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class ActiveFudai extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**派发福袋 */
    public static function sendbox($uid, $count = 10000, $people = 20)
    {
        return self::create(['user_id' => $uid, 'coin' => $count, 'people' => $people]);
    }
}
