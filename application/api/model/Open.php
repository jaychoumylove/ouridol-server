<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class Open extends Base
{
    //

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name');
    }

    /**获取开屏图 */
    public static function getRankList($page, $size, $sort)
    {
        $list = self::with('Star')->where('1=1')->order('hot desc,id desc')->page($page, $size)->select();
        return $list;
    }

    /**增加开屏图人气 */
    public static function addHot($id, $uid, $hot)
    {
        self::where('id', $id)->update(['hot' => Db::raw('hot+' . $hot)]);

        $isDone = OpenRank::where('user_id', $uid)->where('open_id', $id)->update(['count' => Db::raw('count+' . $hot)]);
        if (!$isDone) {
            $isDone = OpenRank::create([
                'user_id' => $uid,
                'open_id' => $id,
                'count' => $hot
            ]);
        }
    }
}
