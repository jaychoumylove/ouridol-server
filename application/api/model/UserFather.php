<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\base\service\Common;

class UserFather extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'son', 'id')->field('id,nickname,avatarurl');
    }

    /**结成师徒关系 */
    public static function join($rer_user_id, $uid)
    {
        if ($rer_user_id == $uid) return;
        // 师傅与徒弟需在同一个圈子
        if (UserStar::where(['user_id' => $rer_user_id])->value('star_id') == UserStar::where(['user_id' => $uid])->value('star_id')) {
            // 不能是别人徒弟
            if (!self::get(['son' => $uid])) {
                // 师徒关系不能逆转
                if (!self::get(['father' => $uid, 'son' => $rer_user_id])) {

                    self::create([
                        'father' => $rer_user_id,
                        'son' => $uid,
                    ]);

                    UserStar::where(['user_id' => $uid])->update(['thisday_count' => 0]);
                } else {
                    Common::res(['msg' => '你是邀请人的师傅，不能成为他的徒弟']);
                }
            } else {
                Common::res(['msg' => '已经是别人的徒弟了']);
            }
        } else {
            Common::res(['msg' => '不是同一圈子']);
        }
    }
}
