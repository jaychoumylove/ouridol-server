<?php
namespace app\api\model;

use app\base\model\Base;

class UserItem extends Base
{
    public static function getItem($uid)
    {
        return self::get(['uid' => $uid]);
    }
}
