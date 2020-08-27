<?php
namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\CfgSprite;
use app\api\model\CfgUserLevel;
use app\api\model\Star;
use app\api\model\StarRank;
use app\api\model\User;
use app\api\model\UserExt;
use app\api\model\UserStar;
use app\api\model\UserSprite;
use app\api\model\UserStarGuardian;
use app\base\controller\Base;
use app\base\service\Common;
use think\Db;

class ActivityGuardian extends Base
{

    /**
     * 守护爱豆时间列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */

    public function getList()
    {
        $this->getUser();

        $list = [];
        $activeTime = Cfg::getCfg('is_guardian_active');
        $active_time['start_time_text'] = date("m",strtotime($activeTime['start_time'])).'月'.date("d",strtotime($activeTime['start_time'])).'日';
        $active_time['end_time_text'] = date("m",strtotime($activeTime['end_time'])).'月'.date("d",strtotime($activeTime['end_time'])).'日';
        $today_time_text = date("m").'月'.date("d").'日';
        $today_hour = date('H');
        $star_id = UserStar::where('user_id',$this->uid)->value('star_id');
        $sprite_level = UserSprite::where('user_id',$this->uid)->value('sprite_level');
        $guardian_times = UserStarGuardian::where('user_id',$this->uid)->where('star_id',$star_id)->whereTime('create_time','d')->count();
        $total_need_stone = 0;
        if($sprite_level<30){
            $total_need_stone = CfgSprite::where('level','<',30)->where('level','>=',$sprite_level)->sum('need_stone');
        }
        for($i=0;$i<24;$i++){

            $list[$i] = [];
            $start_time = strtotime(date('Y-m-d')) + $i*3600;
            $end_time = strtotime(date('Y-m-d')) + ($i+1)*3600;
            $res = UserStarGuardian::with('User')->where('star_id',$star_id)->where('start','<=',$start_time)->where('end','>=',$end_time)->find();
            $res = json_decode(json_encode($res),TRUE);
            if($res) $list[$i] = $res;
            if(date('H')>$i) $list[$i]['timeout'] = true;
        }

        Common::res([
            'data' => [
                'list'=>$list,
                'sprite_level'=>$sprite_level,
                'total_need_stone'=>$total_need_stone,
                'guardian_times'=>$guardian_times,
                'active_time'=>$active_time,
                'today_time_text'=>$today_time_text,
                'today_hour'=>$today_hour,
            ]

        ]);

    }

    /**
     * 守护爱豆
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function guardian()
    {
        $this->getUser();
        if(!Ext::is_start('is_guardian_active')) Common::res(['code' => 1, 'msg' => '守护活动已过']);

        $index = $this->req('index', 'integer');
        if(date('H')>$index) Common::res(['code' => 1, 'msg' => '该段守护时间已过']);

        $sprite_level = UserSprite::where('user_id',$this->uid)->value('sprite_level');
        if($sprite_level<30) Common::res(['code' => 1, 'msg' => '精灵满30级才可以守护爱豆']);

        $star_id = UserStar::where('user_id',$this->uid)->value('star_id');
        $start_time = strtotime(date('Y-m-d')) + $index*3600;
        $end_time = strtotime(date('Y-m-d')) + ($index+1)*3600;
        $is_exist = (new UserStarGuardian)->readMaster()->where('star_id',$star_id)->where('start','<=',$start_time)->where('end','>=',$end_time)->count();
        if($is_exist) Common::res(['code' => 1, 'msg' => '该时间段已经有人守护了']);

        $guardian_times = (new UserStarGuardian)->readMaster()->where('user_id',$this->uid)->where('star_id',$star_id)->whereTime('create_time','d')->count();
        if($guardian_times && $guardian_times>($sprite_level-30)) Common::res(['code' => 1, 'msg' => '今日守护次数已用完']);

        Db::startTrans();
        try {

            $isDone1 = UserExt::where('user_id', $this->uid)->update([
                'total_guardian_times' => Db::raw('total_guardian_times+1'),
                'last_guardian_add_time' => time(),
            ]);

            $isDone2 = StarRank::where('star_id', $star_id)->update([
                'total_guardian_times' => Db::raw('total_guardian_times+1'),
                'last_guardian_add_time' => time(),
            ]);
            if($isDone1 && $isDone2) {
                //守护排名
                UserStarGuardian::create([
                    'user_id'=>$this->uid,
                    'star_id'=>$star_id,
                    'start'=>$start_time,
                    'end'=>$end_time,
                ]);

            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }



        Common::res([]);
    }

    public function rankList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $rank_type = input('rank_type', '1');

        if($rank_type == 1){
            $list = StarRank::with('Star')->where('total_guardian_times','>',0)->field('id,star_id,total_guardian_times')->order('total_guardian_times desc,last_guardian_add_time asc')
                ->page($page, $size)->select();
        }else{
            $list = UserExt::field('id,user_id,total_guardian_times')->where('total_guardian_times','>',0)->order('total_guardian_times desc,last_guardian_add_time asc')
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


    /**
     * 是否守护爱豆中
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function is_guardian($star_id)
    {

        $res = UserStarGuardian::with('User')->where('star_id',$star_id)->where('start','<=',time())->where('end','>=',time())->find();
        if($res){
            $res['time_text'] = date('H:i',$res['start']).'-'.date('H:i',$res['end']);
        }

        return $res;
    }

    
}