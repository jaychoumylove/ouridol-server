<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Model;

class CfgBadge extends Base
{
    public static function getAll($uid)
    {
        $data['list'][0] = self::where('type',1)->select();
        $data['list'][1] = self::where('type',2)->select();
        $data['list'][2] = self::where('type',3)->select();
        $data['list'][3] = self::where('type',4)->select();
        $data['list'][4] = self::where('type',5)->select();
        // 正佩戴的徽章
        $curBadgeId = UserExt::where('user_id', $uid)->value('badge_id');
        $data['useBadgeIds'] = json_decode($curBadgeId);
        $data['getCurBadge'] = self::getCurBadge($uid);

        return $data;
    }

    /**徽章使用 */
    public static function badgeUse($uid,$badgeId)
    {
        $info = CfgBadge::get($badgeId);
        if(!$info)Common::res(['code' => 100]);

        $badge_id = (new UserExt)->readMaster()->where('user_id', $uid)->value('badge_id');
        if($badge_id){
            $badgeids = json_decode($badge_id);
            if(in_array($badgeId,$badgeids)){
                Common::res(['code' => 1, 'msg' => '正在佩戴中']);
            }else{
                if($info['type']==1){
                    $badgeids[0]= $badgeId-0;
                }elseif ($info['type']==2){
                    $badgeids[1]= $badgeId-0;
                }elseif ($info['type']==3){
                    $badgeids[2]= $badgeId-0;
                }elseif ($info['type']==4){
                    $badgeids[3]= $badgeId-0;
                }elseif ($info['type']==5){
                    $badgeids[4]= $badgeId-0;
                }
            }
        }else{
            $badgeids = [$badgeId];
        }
        $isDone = UserExt::where('user_id', $uid)->update(['badge_id' => json_encode($badgeids)]);
        if (!$isDone) {
            Common::res(['code' => 1, 'msg' => '佩戴失败']);
        }
    }

    /**徽章取消使用 */
    public static function badgeCancel($uid,$badgeId)
    {
        $info = CfgBadge::get($badgeId);
        if(!$info)Common::res(['code' => 100]);

        $badge_id = (new UserExt)->readMaster()->where('user_id', $uid)->value('badge_id');
        if(!$badge_id) return;

        $badgeids = json_decode($badge_id);
        if(!in_array($badgeId,$badgeids)){
            Common::res(['code' => 1, 'msg' => '已取消佩戴']);
        }else{
            if($info['type']==1){
                $badgeids[0]= 0;
            }elseif ($info['type']==2){
                $badgeids[1]= 0;
            }elseif ($info['type']==3){
                $badgeids[2]= 0;
            }elseif ($info['type']==4){
                $badgeids[3]= 0;
            }elseif ($info['type']==5){
                $badgeids[4]= 0;
            }
        }
        $isDone = UserExt::where('user_id', $uid)->update(['badge_id' => json_encode($badgeids)]);
        if (!$isDone) {
            Common::res(['code' => 1, 'msg' => '取消佩戴失败']);
        }
    }

    public static function getCurBadge($user_id)
    {
        $badgeList =[];
        $typeList = [1,2,3,4,5];//1 拉新 2 贡献 3 打榜天数 4 偷心 5 活动徽章'
        foreach ($typeList as $key=>$value){
            $count = 0;
            if($value==1){
                $count = UserStar::where('user_id',$user_id)->value('total_count');
            }elseif ($value==2){
                $count = UserRelation::where(['rer_user_id' => $user_id, 'status' => ['in', [1, 2]]])->count();
            }elseif ($value==3){
                $count = UserStar::where('user_id',$user_id)->value('send_hot_days');
            }elseif ($value==4){
                $count = Rec::where('user_id',$user_id)->where('type',1)->sum('coin');
            }

            $badgeList[$key]['id'] = CfgBadge::where('count', '<=', $count)->where('type',$value)->order('id desc')->value('id');;
            $badgeList[$key]['count'] = $count?$count:0;
        }

        return $badgeList;
    }
}
