<?php

namespace app\api\model;

use app\base\model\Base;

class Cfg extends Base
{
    public static function getCfg($key)
    {
        $value = self::where(['key' => $key])->value('value');

        $res = json_decode($value, true);

        if ($res) return $res;
        else return $value;
    }
}
