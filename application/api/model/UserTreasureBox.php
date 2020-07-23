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

        foreach ($logList as &$value) {
            $value['treasure_box'] = CfgTreasureBox::where('id',$value['treasure_box_id'])->field('id,prizeName,imgsrc')->find();
        }
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

    public static function openBox($uid, $self, $index)
    {

        //验证能否开启宝箱
        if ($uid != $self) {
            if ($index == 0 || $index > 5) Common::res(['code' => 1, 'msg' => '该宝箱不能开启']);
            $conditions1 = (new UserRelation)->readMaster()->where(['rer_user_id' => $self, 'ral_user_id' => $uid])->count();
            $conditions2 = (new UserRelation)->readMaster()->where(['rer_user_id' => $uid, 'ral_user_id' => $self])->count();
            if (!$conditions1 && !$conditions2) Common::res(['code' => 1, 'msg' => '您还不是他的好友']);

        } else {
            if ($index != 0 || $index > 5) Common::res(['code' => 1, 'msg' => '该宝箱不能开启']);
        }

        //检查时间段
        $checkTimeInfo = self::checkTime();

        //抽取一个奖品
        $treasureBoxList = CfgTreasureBox::all();
        $treasureBoxList = json_decode(json_encode($treasureBoxList, JSON_UNESCAPED_UNICODE), true);
        $data = Common::lottery($treasureBoxList);

        if ($data['type'] == 0) {
            $data['num'] = 1;
        } elseif ($data['type'] == 1) {
            $data['num'] = mt_rand(100, 1000);
            $currency = ['coin' => $data['num']];
        } elseif ($data['type'] == 2) {
            $data['num'] = mt_rand(1, 3);
            $currency = ['stone' => $data['num']];
        }

        Db::startTrans();
        try {

            //帮助开启
            if ($uid != $self) {

                $is_help = (new UserTreasureBox())->readMaster()->where(['user_id' => $uid, 'help_user_id' => $self])->whereTime('create_time','d')->find();
                if ($is_help) Common::res(['code' => 1, 'msg' => '已经帮助过该好友了']);

                $is_help_count = (new UserTreasureBox())->readMaster()->where(['help_user_id' => $self])->whereTime('create_time','d')->count();
                if ($is_help_count>=100) Common::res(['code' => 1, 'msg' => '每日最多帮开100次宝箱']);

                $treasure_box_times = (new UserExt)->readMaster()->where('user_id', $self)->value('treasure_box_times');
                if ($treasure_box_times>0) {
                    UserExt::where('user_id', $self)->update(['treasure_box_times'=>Db::raw('treasure_box_times-1'),]);
                }else{
                    (new User())->change($self, ['stone' => -20], ['type' => 46, 'content' => '使用灵丹帮助好友开启宝箱']);
                }

                if ($data['type'] != 0) {
                    (new User())->change($uid, $currency, ['type' => 44, 'content' => '开启宝箱']);
                    (new User())->change($self, $currency, ['type' => 45, 'content' => '帮助好友开启宝箱获得']);
                } else {
                    UserProp::addProp($uid, $data['prop_id'], 1);
                    UserProp::addProp($self, $data['prop_id'], 1);
                }

                $res = UserRelation::whereOr(['rer_user_id' => $self, 'ral_user_id' => $uid])->update([
                    'intimacy' => Db::raw('intimacy+1'),
                ]);
                if(!$res){
                    UserRelation::whereOr(['rer_user_id' => $uid, 'ral_user_id' => $self])->update([
                        'intimacy' => Db::raw('intimacy+1'),
                    ]);
                }
            }else{
                if ($data['type'] != 0) {
                    (new User())->change($uid, $currency, ['type' => 44, 'content' => '开启宝箱']);
                } else {
                    UserProp::addProp($uid, $data['prop_id'], 1);
                }
            }

            //添加记录
            $addRes = self::create([
                'user_id' => $uid,
                'treasure_box_id' => $data['id'],
                'index' => $index,
                'count' => $data['num'],
                'type' => $data['type'],
                'create_date_hour' => $checkTimeInfo['date'],
                'help_user_id' => $uid==$self?'':$self,
            ]);
            if(!$addRes) Common::res(['code' => 1, 'msg' => '宝箱已经开启过了']);


            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        return $data;

    }

    public static function openOtherBox($uid, $self)
    {

        $checkTimeInfo = self::checkTime();
        $indexs = [1,2,3,4,5];
        $index = 0;
        foreach ($indexs as $value){
            $is_get_indexs = (new UserTreasureBox())->readMaster()->where(['user_id' => $uid])->where('index',$value)->where('create_date_hour',$checkTimeInfo['date'])->field('id')->find();
            if(!$is_get_indexs){
                $index = $value;
                break;
            }
        }
        if($index ==0)Common::res(['code' => 1, 'msg' => '当前好友宝箱已经被开完了']);

        $data = self::openBox($uid, $self, $index);

        return $data;
    }



}
