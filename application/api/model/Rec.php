<?php

namespace app\api\model;

use app\base\model\Base;

class Rec extends Base
{
    //

    const ACTIVE618GIFT = 40; // 618活动领取"怦然心动"
    const EXCHANGE = 39; // 灵丹兑换
    const ACTIVEDWJGIFT = 41; // 灵丹兑换
    const YINGYUAN = 42; // 打卡赢取能量1000

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
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

        foreach ($logList as &$value) {
            // 转译$0 占位符
            $list = json_decode($value['content'], true);
            if ($list) {
                for ($i = 0; $i < count($list); $i++) {
                    $value['type']['content'] = str_replace('$' . $i, $list[$i],  $value['type']['content']);
                }
            }
            if($value['type']['id']==47 && $value['target_user']['nickname']){
                $value['type']['content'] = '膜拜大神['.$value['target_user']['nickname'].']';

            }elseif ($value['type']['id']==48 && $value['target_user']['nickname']){
                $value['type']['content'] = '被['.$value['target_user']['nickname'].']膜拜';
            }

            if($value['type']['id']==5 && $value['target_user']['nickname']){
                $value['type']['content'] = '领取徒弟['.$value['target_user']['nickname'].']的收益';
            }
        }
        return $logList;
    }

    /**存入日志 */
    public static function addRec($log)
    {
        // parent::clear(10);
        return parent::create($log);
    }
}
