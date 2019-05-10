<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class CfgShare extends Base
{
    //

    public static function getOne()
    {
        $text = self::where(false)->orderRaw('rand()')->value('content');
        $text = str_replace('<p>', '', $text);
        $text = str_replace('</p>', '', $text);

        return $text;
    }
}
