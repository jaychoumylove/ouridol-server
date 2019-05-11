<?php

namespace app\api\model;

use app\base\model\Base;

class UserRelation extends Base
{
    //

    public function User()
    {
        return $this->belongsTo('User', 'ral_user_id', 'id')->field('id,nickname,avatarurl');
    }

    public static function saveNew($ral_user_id, $rer_user_id)
    {
        if ($ral_user_id == $rer_user_id) return;
        $item = self::get(['ral_user_id' => $ral_user_id]);
        if (!$item) {
            self::create([
                'rer_user_id' => $rer_user_id,
                'ral_user_id' => $ral_user_id,
            ]);
        }
    }

    public static function fixByType($type, $res, $uid)
    {
        if ($type == 1) { // 宠物页面邀请列表，统计收益
            if ($res) {
                foreach ($res as $key => &$value) {
                    $value['sprite'] =  UserSprite::getInfo($value['user']['id']);
                    // 排序
                    $sort[$key] = $value['sprite']['earn'];
                }

                array_multisort($sort, SORT_DESC, $res);
            } else {
                $sprite = UserSprite::getInfo($uid);
                if($sprite['skillone_times'] == 0){
                    // 给一个虚拟好友
                    $vrUser['user'] = User::where(['id' => 1])->field('id,nickname,avatarurl')->find();
                    $vrUser['sprite']['earn'] = 100;
                    $res[] = $vrUser;
                }
                
            }
        } else if ($type = 2) {
            // 师徒页 统计用户今日贡献
            if ($res) {
                foreach ($res as $key => &$value) {
                    $value['user_star'] = $value['user']['user_star'];
                    $value['user_earn'] = ceil(Cfg::getCfg('father_earn_per') * $value['user_star']['thisday_count']);
                    // 排序
                    $sort[$key] = $value['user_star']['thisday_count'];
                }
                array_multisort($sort, SORT_DESC, $res);
            }
        }

        return $res;
    }
}
