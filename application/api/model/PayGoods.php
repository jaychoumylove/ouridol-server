<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class PayGoods extends Base
{
    //

    public function Item()
    {
        return $this->belongsTo('CfgItem', 'item_id', 'id');
    }
}
