<?php


namespace app\api\model;

use app\base\model\Base;

class UserStarGuardian extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s,head_img_l');
    }
}