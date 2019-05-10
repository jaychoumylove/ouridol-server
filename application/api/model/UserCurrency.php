<?php

namespace app\api\model;

use app\base\model\Base;

class UserCurrency extends Base
{
    public static function getCurrency($uid)
    {
        $item = self::get(['uid' => $uid]);
        if (!$item) {
            $item = self::create([
                'uid' => $uid,
                'coin' => 0,
                'stone' => 0,
                'trumpet' => 0,
            ]);
        }
        unset($item['id']);
        unset($item['create_time']);
        unset($item['uid']);
        return $item;
    }
}
