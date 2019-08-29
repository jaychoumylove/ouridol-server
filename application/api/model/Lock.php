<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

/**数据锁 */
class Lock extends Base
{
    //

    public static function getVal($key)
    {
        return self::where('key', $key)->field('value,update_time as time')->find();
    }

    public static function setVal($key, $value)
    {
        return self::where('key', $key)->update([
            'value' => $value
        ]);
    }
}
