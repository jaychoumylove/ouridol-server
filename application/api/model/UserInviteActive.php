<?php

namespace app\api\model;

use app\base\model\Base;

class UserInviteActive extends Base
{
    public static function inviteUserLog($uid, $page, $size)
    {
        $logList = self::where('user_id',$uid)->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

        foreach ($logList as &$value) {

            $nickname = User::where('id',$value['usered_id'])->value('nickname');
            if($value['type']==1){
                $value['content'] = '邀请新用户['.$nickname.']';
                $value['energy'] = 3;
            }else{
                $value['content'] = '老用户['.$nickname.']回归';
                $value['energy'] = 1;
            }
        }
        return $logList;
    }
}
