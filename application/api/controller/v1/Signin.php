<?php


namespace app\api\controller\v1;


use app\api\model\CfgDaySignin;
use app\api\model\CfgItem;
use app\api\model\CfgSigninGift;
use GatewayWorker\Lib\Gateway;
use app\api\model\Rec;
use app\api\model\Star as StarModel;
use app\api\model\StarRank as StarRankModel;
use app\api\model\User;
use app\api\service\User as ServiceUser;
use app\api\model\DaySigninUser;
use app\base\controller\Base;
use think\Db;
use app\base\service\Common;

class Signin extends Base
{
    /**签到页面*/
    public function getSignin(){
        $this->getUser();
        $signinCfg=CfgDaySignin::select();
        $star_id = input('starid');
        $gift_info=CfgSigninGift::all();
        foreach ($gift_info as $key=>&$value){
            $value['item_info']=json_decode(CfgItem::where('id','in',$value['item_id'])->find(),true);
        }
        foreach ($signinCfg as $key=>&$value){
            $startTime = strtotime($value['start_time'] . ':00');
            $endTime = strtotime($value['end_time'] . ':00');
            $value['signin_time'] = date('Y-m-d', time()) . ' ' . $value['start_time'] . ':00';
            if (time() < $startTime) {
                // 还没开始
                $value['status'] = 1;
            } else if (time() >= $startTime && time() <= $endTime) {
                $singninTime=CfgDaySignin::status();
                $data['joinAll']=DaySigninUser::where(['signin_time'=>$singninTime['whole_time'],'star_id'=>$star_id])->count();
                $data['isJoin']=DaySigninUser::where(['user_id'=>$this->uid,'signin_time'=>$singninTime['whole_time'],'star_id'=>$star_id])->count();
                $data['timeLeft']=$singninTime['timeLeft'];
                // 正签到时间段
                $value['status'] = 2;
            } else {
                // 签到已结束
                $value['status'] = 0;
            }
        }
        $data['gift_info']=$gift_info;
        $data['signinGroup']=[];
        $data['signinCfg']=$signinCfg;
        Common::res(['data' => $data]);
    }
    /** 签到*/
    public function joinSignin(){
        $this->getUser();
        $star_id=$this->req('starid','integer');
        $type=$this->req('type','integer');
        $nowTime=date('H:i',time());
        $joinStatus=CfgDaySignin::where('start_time','<=',$nowTime)->where('end_time','>=',$nowTime)->find();
        if(!$joinStatus){
            Common::res(['code' => 2, 'msg' => '未到时间']);
        }
        $singninTime=CfgDaySignin::status();
        if($type==1){
            $singninTime['coin']=$singninTime['coin']*10;
        }else{
            $singninTime['coin']=$singninTime['coin'];
        }
        $isJoin=DaySigninUser::where(['user_id'=>$this->uid,'signin_time'=>$singninTime['whole_time']])->count();
        if($isJoin){
            Common::res(['code' => 2, 'msg' => '已签到']);
        }

        Db::startTrans();
        try {
            // 新增签到用户
            DaySigninUser::newSignin($this->uid,$star_id,$singninTime['whole_time']);
            // 日志
            (new ServiceUser())->change($this->uid, [
                'coin' => $singninTime['coin'],
            ], [
                'type' => 35
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
        $this->sendGift($star_id,$singninTime);
        Common::res(['code'=>0,'data' => $singninTime]);

    }
    public function sendGift($star_id,$singninTime){
        //已签到人数
        $siginedCount=DaySigninUser::where(['star_id'=>$star_id,'signin_time'=>$singninTime['whole_time']])->count();

        //签到结算 配置项
        $signGift = CfgSigninGift::where('count','<=',$siginedCount)->order('count desc')->find();

        //
        $this->update_gift($star_id,$signGift['sign_gift'],$signGift['item_id']);


    }

    //更改礼物状态
    public  function update_gift($star_id,$sign_gift,$item_id){

        $isDone = StarModel::where(['id'=>$star_id,'sign_gift'=>$sign_gift-1])->update([
            'sign_gift'=>$sign_gift
        ]);

        if($isDone) $this->pushGift($star_id,$item_id);
    }

    public function pushGift($star_id,$item_id){

        $itemInfo=CfgItem::where('id',$item_id)->find();
        $star=StarModel::where('id', $star_id)->find();
        Db::startTrans();
        try {
            StarRankModel::where(['star_id' => $star_id])->update([
                'week_hot' => Db::raw('week_hot+' . $itemInfo['count']),
                'month_hot' => Db::raw('month_hot+' . $itemInfo['count']),
            ]);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);

        }

        // 圈内推送
        Gateway::sendToGroup('star_' . $star_id, json_encode([
            'type' => 'sendItem',
            'data' => [
                'itemicon' => $itemInfo['icon'],
                'itemname' => $itemInfo['name'],
                'username' => '系统签到',
                'avatar' => $star['head_img_s'],
                'starname' =>$star['name']
            ]
        ], JSON_UNESCAPED_UNICODE));

        // 全服推送
        Gateway::sendToAll(json_encode([
            'type' => 'sayworld',
            'data' => [
                'avatarurl' => $star['head_img_s'],
                'starname' => $star['name'],
                'icon' => $itemInfo['icon'],
                'nickname' =>'系统签到',
            ],
        ], JSON_UNESCAPED_UNICODE));

    }
    //签到时间段结束 签到礼物状态清零
    public function clear_gift(){
        Db::startTrans();
        try {
            StarModel::where('1=1')->update([
                'sign_gift'=>0
            ]);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

}