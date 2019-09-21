<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class Wxgroup extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    /**新增群 */
    public static function groupAdd($open_gid, $starid)
    {
        $isFind = self::where('open_gid', $open_gid)->where('star_id', $starid)->find();
        if (!$isFind) {
            $isFind = self::create([
                'open_gid' => $open_gid,
                'star_id' => $starid
            ]);
        }

        return $isFind['id'];
    }

    /**增加贡献度 */
    public static function userSendHot($uid, $hot)
    {
        // 打一次榜只给参与集结次数最多的群加贡献
        $gid = UserWxgroup::where('user_id', $uid)->order('mass_times desc,mass_join_at desc')->value('wxgroup_id');
        // 个人贡献
        UserWxgroup::where('user_id', $uid)->where('wxgroup_id', $gid)->update([
            'thisday_count' => Db::raw('thisday_count+' . $hot),
        ]);

        // 群贡献
        self::where('id', $gid)->update([
            'total_count' => Db::raw('total_count+' . $hot),
            'thisday_count' => Db::raw('thisday_count+' . $hot),
        ]);
    }
}
