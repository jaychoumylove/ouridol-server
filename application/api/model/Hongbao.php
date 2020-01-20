<?php

namespace app\api\model;

use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class Hongbao extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**发红包 */
    public static function sendbox($uid, $count = 10000, $people = 10)
    {
        $latest = self::where('user_id', $uid)->order('id desc')->find();
        if (time() - strtotime($latest['create_time']) > 1800) {
            $id = self::create(['user_id' => $uid, 'coin' => $count, 'people' => $people]);
        } else {
            $id = $latest['id'];
        }

        return $id;
    }
}
