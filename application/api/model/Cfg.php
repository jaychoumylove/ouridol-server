<?php

namespace app\api\model;

use app\base\model\Base;

class Cfg extends Base
{
    const FORBIDDEN_TIME = 'forbidden_time';
    const REPORT_REASON = 'report_reason';

    public static function getCfg($key)
    {
        $value = self::where(['key' => $key])->value('value');

        $res = json_decode($value, true);

        if ($res) return $res;
        else return $value;
    }

    public static function getList()
    {
        $list = self::all(['show' => 1]);
        foreach ($list as $value) {
            $val = json_decode($value['value'], true);
            if (!$val) $val = $value['value'];

            $res[$value['key']] = $val;
        }

        return $res;
    }
}
