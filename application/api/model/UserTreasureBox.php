<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class UserTreasureBox extends Base
{
    public static function getList($uid, $page, $size)
    {
        $logList = self::where(['user_id' => $uid])->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

//        foreach ($logList as &$value) {
//
//        }
        return $logList;
    }

    public static function checkTime()
    {

        $time1 = date('Y-m-d') . ' 00:00:00';
        $time2 = date('Y-m-d') . ' 11:00:00';
        $time3 = date('Y-m-d') . ' 18:00:00';
        if (date('Y-m-d H:i:s') > $time1 && date('Y-m-d H:i:s') < $time2) {
            $res['date'] = $time1;
            $res['nextTime'] = strtotime($time2) - time();
            $res['nextTimeText'] = '11:00';
        } elseif (date('Y-m-d H:i:s') > $time2 && date('Y-m-d H:i:s') < $time3) {
            $res['date'] = $time2;
            $res['nextTime'] = strtotime($time3) - time();
            $res['nextTimeText'] = '18:00';
        } else {
            $res['date'] = $time3;
            $res['nextTime'] = strtotime(date('Y-m-d', strtotime('+1 day'))) - time();
            $res['nextTimeText'] = '00:00';
        }
        return $res;
    }

    public static function openBox($uid,$self,$index){

        Common::res(['code' => 1, 'msg' => '未开启']);
        //验证
        if($uid!=$self){
            if($index==0)Common::res(['code' => 1, 'msg' => '该宝箱不能开启']);
            $conditions1 = UserRelation::where(['rer_user_id'=>$self,'ral_user_id'=>$uid])->count();
            $conditions2 = UserRelation::where(['rer_user_id'=>$uid,'ral_user_id'=>$self])->count();
            if(!$conditions1 && !$conditions2)Common::res(['code' => 1, 'msg' => '您还不是他的好友']);

        }else{
            if($index!=0)Common::res(['code' => 1, 'msg' => '该宝箱不能开启']);
        }

        $checkTimeInfo = self::checkTime();
        $check = self::where(['user_id'=>$uid,'index'=>$index,'create_date_hour'=>$checkTimeInfo['date']])->find();
        if($check)Common::res(['code' => 1, 'msg' => '宝箱已经开启过了']);

        //抽取一个奖品
        $treasureBoxList = CfgTreasureBox::all();
        $treasureBoxList = json_decode(json_encode($treasureBoxList, JSON_UNESCAPED_UNICODE), true);
        $data = Common::lottery($treasureBoxList);

        if($data['type']==0){
            $prop_id = $data['prop_id'];
        }elseif ($data['type']==1){
            $num = mt_rand(100,1000);
            $currency=['coin' => $num];
        }elseif ($data['type']==2){
            $num = mt_rand(1,3);
            $currency=['coin' => $num];
        }

        var_dump($data);die;

        Db::startTrans();
        try {

            //todo
//            UserTreasureBox::create([
//
//            ]);

            if($data['type']!=0){
                (new User())->change($uid, $currency,['type'=>44,''=>'开启宝箱']);
                //帮助开启
                if($uid!=$self){
                    (new User())->change($uid, $currency,['type'=>44,''=>'帮助好友开启宝箱']);
                    UserRelation::whereOr(['rer_user_id'=>$self,'ral_user_id'=>$uid])->update([
                        'intimacy' => Db::raw('intimacy+1'),
                    ]);
                    UserRelation::whereOr(['rer_user_id'=>$uid,'ral_user_id'=>$self])->update([
                        'intimacy' => Db::raw('intimacy+1'),
                    ]);
                }
            }else{
                UserProp::addProp($uid, $prop_id, 1);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        return $data;

    }



}
