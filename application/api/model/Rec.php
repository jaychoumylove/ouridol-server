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

    public static function getList($uid, $page, $size)
    {
        $logList = self::with('Type,TargetUser,TargetStar')->where(['user_id' => $uid])->order('id desc')->page($page, $size)->select();
        $logList =   json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);
        foreach ($logList as &$value) {
            $value['type']['content'] = str_replace('$0', json_decode($value['content'], true)[0],  $value['type']['content']);
        }
        return $logList;
    }
}
