<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use app\api\model\User as UserModel;
use think\Db;
use think\Model;

class UserTreasureBox extends Base
{
    public static function getList($uid, $page, $size)
    {
        $logList = self::where('user_id',$uid)->whereOr('help_user_id',$uid)->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

        foreach ($logList as &$value) {
            $value['treasure_box'] = CfgTreasureBox::where('id',$value['treasure_box_id'])->field('id,prizeName,imgsrc')->find();
            if($value['help_user_id'] && $value['help_user_id'] != $uid){
                $value['help_user_info'] = UserModel::where('id',$value['help_user_id'])->field('id,nickname,avatarurl')->find();
            }elseif ($value['help_user_id'] && $value['help_user_id'] == $uid){
                $value['helped_user_info'] = UserModel::where('id',$value['user_id'])->field('id,nickname,avatarurl')->find();
            }
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

            $uid_star_id = (new UserStar())->where(['user_id' => $uid])->value('star_id');
            $self_star_id = (new UserStar())->where(['user_id' => $self])->value('star_id');
            if (!$self_star_id || ($uid_star_id!=$self_star_id)) Common::res(['code' => 1, 'msg' => '你们不在同一个圈子']);
            $uid_platform = (new UserModel())->where(['id' => $uid])->value('platform');
            $self_platform = (new UserModel())->where(['id' => $self])->value('platform');
            if($uid_platform != $self_platform){
                if($uid_platform == 'MP-WEIXIN' && $self_platform == 'MP-QQ'){
                    Common::res(['code' => 1, 'msg' => '不能给微信用户开宝箱了']);
                }else if($self_platform == 'MP-WEIXIN' && $uid_platform == 'MP-QQ'){
                    Common::res(['code' => 1, 'msg' => '不能给QQ用户开宝箱了']);
                }
            }

        } else {
            if ($index != 0 || $index > 5) Common::res(['code' => 1, 'msg' => '该宝箱不能开启']);
        }

        //检查时间段
        $checkTimeInfo = self::checkTime();

        //抽取一个奖品
        $treasureBoxList = CfgTreasureBox::where('chance','>',0)->select();
        $treasureBoxList = json_decode(json_encode($treasureBoxList, JSON_UNESCAPED_UNICODE), true);
        $data = Common::lottery($treasureBoxList);

        if ($data['type'] == 0) {//道具
            $data['num'] = 1;

            $reelect =false;//是否重选
            if($data['id']==18){//福袋只能一天获取一次
                $treasure_box18 =(new UserTreasureBox())->readMaster()->where(['user_id' => $uid, 'treasure_box_id' => 18])->whereTime('create_time','d')->find();
                if($treasure_box18){
                    $reelect = true;
                }
            }else{//其他道具只能一天获取三次
                $treasure_box_get_count =(new UserTreasureBox())->readMaster()->where(['user_id' => $uid, 'treasure_box_id' => $data['id']])->whereTime('create_time','d')->count();
                if($treasure_box_get_count>=3){
                    $reelect = true;
                }
            }
            if($reelect){
                $data = CfgTreasureBox::where('type',1)->find();
                $data['num'] = mt_rand(100, 500);
                $currency = ['coin' => $data['num']];
            }

        } elseif ($data['type'] == 1) {//能量
            $data['num'] = mt_rand(100, 500);
            $currency = ['coin' => $data['num']];
        } elseif ($data['type'] == 2) {//灵丹
            $data['num'] = mt_rand(1, 3);
            $currency = ['stone' => $data['num']];

            $treasure_box14 =(new UserTreasureBox())->readMaster()->where(['user_id' => $uid, 'treasure_box_id' => 14])->whereTime('create_time','d')->count();
            if($treasure_box14>=3){
                $data = CfgTreasureBox::where('type',1)->find();
                $data['num'] = mt_rand(100, 500);
                $currency = ['coin' => $data['num']];
            }
        }

        Db::startTrans();
        try {

            //帮助开启
            if ($uid != $self) {

                $is_help = (new UserTreasureBox())->readMaster()->where(['user_id' => $uid, 'help_user_id' => $self])->whereTime('create_time','d')->find();
                if ($is_help) Common::res(['code' => 1, 'msg' => '今日已经帮助过该好友了']);

                $is_help_count = (new UserTreasureBox())->readMaster()->where(['help_user_id' => $self])->whereTime('create_time','d')->count();
                if ($is_help_count>=100) Common::res(['code' => 1, 'msg' => '每日最多帮开100次宝箱']);

                //帮助开箱次数加一
                UserExt::where('user_id', $self)->update(['help_open_times' => Db::raw('help_open_times+1'),]);

                //是否剩余开箱次数，无则使用灵丹开启
                $treasure_box_times =  UserExt::where('user_id', $self)->where('treasure_box_times','>',0)->update(['treasure_box_times'=>Db::raw('treasure_box_times-1'),]);
                if (!$treasure_box_times) {
                    (new User())->change($self, ['stone' => -20], ['type' => 46, 'content' => '使用灵丹帮助好友开启宝箱']);
                }

                //帮助开箱获得奖励
                if ($data['type'] != 0) {
                    (new User())->change($uid, $currency, ['type' => 44, 'content' => '开启宝箱']);
                    (new User())->change($self, $currency, ['type' => 45, 'content' => '帮助好友开启宝箱获得']);
                } else {
                    UserProp::addProp($uid, $data['prop_id'], 1);
                    UserProp::addProp($self, $data['prop_id'], 1);
                }

                //增加亲密度
                $res = UserRelation::whereOr(['rer_user_id' => $self, 'ral_user_id' => $uid])->update([
                    'intimacy' => Db::raw('intimacy+1'),
                ]);
                if(!$res){
                    UserRelation::whereOr(['rer_user_id' => $uid, 'ral_user_id' => $self])->update([
                        'intimacy' => Db::raw('intimacy+1'),
                    ]);
                }
            }else{
                //开箱获得奖励
                if ($data['type'] != 0) {
                    (new User())->change($uid, $currency, ['type' => 44, 'content' => '开启宝箱']);
                } else {
                    UserProp::addProp($uid, $data['prop_id'], 1);
                }
            }

            $is_get =(new UserTreasureBox())->readMaster()->where(['user_id' => $uid, 'index' => $index, 'create_date_hour' => $checkTimeInfo['date']])->count();
            if($is_get){
                Common::res(['code' => 1, 'msg' => '该宝箱已开启']);
            }

            //添加记录
            self::create([
                'user_id' => $uid,
                'treasure_box_id' => $data['id'],
                'index' => $index,
                'count' => $data['num'],
                'type' => $data['type'],
                'create_date_hour' => $checkTimeInfo['date'],
                'help_user_id' => $uid==$self?'':$self,
            ]);


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
