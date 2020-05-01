<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class UserActive extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    /**获取用户的打卡信息 */
    public static function getOneInfo($uid, $starid, $active_id)
    {
        $data = self::where('user_id', $uid)->where('star_id', $starid)->where('active_id', $active_id)->find();
        // 今日是否已打卡
        $data['is_card_today'] = date('Ymd', strtotime($data['update_time'])) == date('Ymd');

        //用户等级是否达标，1天1级
        $count = UserStar::where('user_id', $uid)->where('star_id', $starid)->order('id desc')->value('total_count');
        $userLevel = CfgUserLevel::where('total', '<=', $count)->max('level');
        $data['is_userlevel_needup'] = $userLevel < (isset($data['total_clocks']) ? $data['total_clocks'] : 1);

        return $data;
    }

    /**明星的活动进度 */
    public static function getProgress($starid, $active_id, $min_clocks)
    {
        // 参与人数
        $res['join_people'] = self::where('star_id', $starid)->where('active_id', $active_id)->where('total_clocks', '>', 0)->count('id');
        // 完成人数
        $res['complete_people'] = self::where('star_id', $starid)->where('active_id', $active_id)->where('total_clocks', '>=', $min_clocks)->count('id');
        return $res;
    }

    /**用户增加打卡数 */
    public static function addClock($uid, $starid, $active_id)
    {
        $isDone = self::where('user_id', $uid)->where('star_id', $starid)->where('active_id', $active_id)->update([
            'total_clocks' => Db::raw('total_clocks+1')
        ]);

        if (!$isDone) {
            self::create([
                'user_id' => $uid,
                'star_id' => $starid,
                'active_id' => $active_id,
                'total_clocks' => 1,
            ]);
        }
    }
}
