<?php

namespace app\api\model;

use app\base\model\Base;

class Rec extends Base
{
    //

    public function Type()
    {
        return $this->belongsTo('CfgRecType', 'type', 'id');
    }
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    public function TargetUser()
    {
        return $this->belongsTo('User', 'target_user_id', 'id')->field('id,nickname,avatarurl');
    }

    public function TargetStar()
    {
        return $this->belongsTo('Star', 'target_star_id', 'id')->field('id,name,head_img_s,head_img_l');
    }
}
