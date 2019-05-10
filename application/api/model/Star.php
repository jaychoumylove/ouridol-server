<?php

namespace app\api\model;

use app\base\model\Base;

class Star extends Base
{
    public function StarRank()
    {
        return $this->hasOne('StarRank', 'star_id', 'id')->field('id', true);
    }
}
