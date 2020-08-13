<?php
namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\CfgUserLevel;
use app\api\model\StarRank;
use app\api\model\Star;
use app\api\model\UserExt;
use app\api\model\UserInviteReward;
use app\api\model\UserRelation;
use app\api\model\UserStar;
use app\api\model\Prop;
use app\api\service\User as UserService;
use app\api\model\User;
use app\base\controller\Base;
use app\base\service\Common;
use think\Db;

class ActiveInvite extends Base
{

    public function invite_new_info()
    {
        $this->getUser();

        $res['notice']=16;//奖励说明
        $res['title_img']='https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EOWV82IkeqFRibMgcWRnrqII5hZrPLCRJyTlzU7BQTKtG8ibTDMK7PlkbHB7LrncYuuMVKmzEaglxQ/0';//主题图片
        $res['my_remaining_time']=strtotime('tomorrow')-time();
        $res['total_coin']=StarRank::where('total_invite_energy','>','0')->sum('total_invite_energy');
        $res['my_invite_info']= UserExt::where(['user_id' => $this->uid])->field('invite_energy,get_new_invite_energy,get_old_invite_energy,total_invite_energy')->find();
        $invite_steps=Cfg::getCfg('invite_steps');
        $invite_steps = json_decode(json_encode($invite_steps),TRUE);
        foreach ($invite_steps as &$step){
            if($step["reward"]['prop']){
                $step["reward"]['prop_img'] = Prop::where('id',$step["reward"]['prop'])->value('img');
            }
        }
        $steps = self::steps($invite_steps,$res['my_invite_info']['invite_energy'],$this->uid);
        $res['steps']=$steps['steps'];
        $res['my_next_invitenum']=$steps['my_next_invitenum'];

        Common::res(['data' => $res]);
    }

    public static function steps($steps,$my_invitenum,$user_id){

        foreach ($steps as $key=>$step){
            $my_next_invitenum = 0;
            $steps[$key]['is_get'] = UserInviteReward::where(['user_id'=>$user_id,'index'=>$key,'create_date'=>date('Y-m-d')])->count();
            if($my_invitenum>=$step['invite_num']){
                $steps[$key]['precent'] = 100;
            }else{
                if($key>0){
                    $my_invitenum =$my_invitenum-$steps[$key-1]['invite_num'];
                    $invite_num =$step['invite_num']-$steps[$key-1]['invite_num'];
                    $steps[$key]['precent'] = ($my_invitenum/$invite_num)*100;
                }else{
                    $steps[$key]['precent'] = ($my_invitenum/$step['invite_num'])*100;
                }
                $my_next_invitenum= $step['invite_num'];
                break;
            }
        }

        return ['steps'=>$steps,'my_next_invitenum'=>$my_next_invitenum];
    }

    public function invite_steps_reward(){

        $this->getUser();
        $index = $this->req('index', 'integer');

        $invite_steps=Cfg::getCfg('invite_steps');
        $invite_steps = json_decode(json_encode($invite_steps),TRUE);
        if($index<0 || $index+1>count($invite_steps))Common::res(['code' => 100]);

        $my_invitenum_today= UserRelation::where(['rer_user_id' => $this->uid, 'status' => ['in', [1,2]]])->whereTime('create_time','d')->count();
        if($invite_steps[$index]['invite_num']>$my_invitenum_today)Common::res(['code' => 1, 'msg' => '还不能领取该奖励,赶快去拉新吧']);

        Db::startTrans();
        try {

            $is_exist = (new UserInviteReward)->readMaster()->where(['user_id'=>$this->uid,'index'=>$index,'create_date'=>date('Y-m-d')])->find();
            if($is_exist)Common::res(['code' => 1, 'msg' => '已经领取过该奖励了']);

            $isDone = UserInviteReward::where(['user_id'=>$this->uid,'index'=>$index])->update([
                'invite_num' => $invite_steps[$index]['invite_num'],
                'reward' => json_encode($invite_steps[$index]['reward']),
                'create_date' => date('Y-m-d'),
            ]);
            if(!$isDone) {
                UserInviteReward::create([
                    'user_id' => $this->uid,
                    'index' => $index,
                    'invite_num' => $invite_steps[$index]['invite_num'],
                    'reward' => json_encode($invite_steps[$index]['reward']),
                    'create_date' => date('Y-m-d'),
                ]);

            }

            (new UserService)->change($this->uid, $invite_steps[$index]['reward'], ['type'=>54,'content'=>'拉新额外奖励']);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        Common::res(['data' => $invite_steps[$index]['reward']]);

    }

    public function groupInviteRank()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $rank_type = input('rank_type', 'group');

        if($rank_type == 'group'){
            $list = StarRank::with('Star')->where('total_invite_energy','>',0)->field('id,star_id,total_invite_energy')->order('total_invite_energy desc,last_invite_add_time asc')
                ->page($page, $size)->select();
        }else{
            $list = UserExt::field('id,user_id,total_invite_energy')->where('total_invite_energy','>',0)->order('total_invite_energy desc,last_invite_add_time asc')
                ->page($page, $size)->select();
            $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
            foreach ($list as &$value) {
                $value['user'] = User::where('id',$value['user_id'])->field('id,nickname,avatarurl')->find();
                $userStar = UserStar::where('user_id', $value['user']['id'])->field('star_id,total_count')->find();
                $value['user']['starname'] = Star::where('id', '=', $userStar['star_id'])->value('name');
                $value['user']['level'] = CfgUserLevel::where('total', '<=', $userStar['total_count'])->max('level');
            }
        }

        Common::res(['data' => $list]);
    }


}
