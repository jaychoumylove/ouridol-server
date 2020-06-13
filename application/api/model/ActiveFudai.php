<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class ActiveFudai extends Base
{
    const FUDAI_ACTIVE = 0.1;
    const MAX_PEOPLE   = 20;
    const LIMIT_TIME = '2020-06-22';
    const FUDAI_OFF = 'FUDAI_OFF';

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**派发福袋 */
    public static function sendbox($uid, $count = 10000, $people = 20)
    {
        $open = self::checkFudai();
        if (empty($open)) return self::FUDAI_OFF;

        return self::create(['user_id' => $uid, 'coin' => $count, 'people' => $people]);
    }

    /**
     * 检查福袋活动是否开启
     * @return bool
     */
    public static function checkFudai ()
    {
        return date('Y-m-d') < self::LIMIT_TIME;
    }
}
