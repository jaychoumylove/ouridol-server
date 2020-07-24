<?php

namespace app\api\controller\v1;

use app\api\model\CfgTreasureBox;
use app\api\model\User;
use app\api\model\UserExt;
use app\api\model\UserTreasureBox;
use app\base\controller\Base;
use app\base\service\Common;

class TreasureBox extends Base
{

    protected $box_notice_id = 38;
    /**
     * 宝箱列表信息
     */
    public function index ()
    {
        $this->getUser();
        $checkTimeInfo = UserTreasureBox::checkTime();
        $res['nextTime']= $checkTimeInfo['nextTime'];
        $res['nextTimeText']= $checkTimeInfo['nextTimeText'];
        $res['box_notice_id']=$this->box_notice_id;//宝箱说明id
        $res['list'] = [[],[],[],[],[],[]];

        foreach ($res['list'] as $key => $value) {

            $is_get_info = UserTreasureBox::where('user_id', $this->uid)->where('index', $key)->where('create_date_hour',$checkTimeInfo['date'])->find();
            if ($is_get_info) {

                $is_get_info['treasure_box']=CfgTreasureBox::get($is_get_info['treasure_box_id']);

                $res['list'][$key] = $is_get_info;

            }
        }

        Common::res(['data' => $res]);
    }

    /**
     * 宝箱信息
     */
    public function info ()
    {
        $this->getUser();
        $user_id = input('user_id');
        $index = input('index');
        if (!$user_id || !$index) Common::res(['code' => 100]);

        $checkTimeInfo = UserTreasureBox::checkTime();
        $is_get_info = UserTreasureBox::where('user_id', $user_id)->where('index', $index)->where('create_date_hour',$checkTimeInfo['date'])->find();
        if($is_get_info){
            $is_get_info['is_open'] = true;
            $is_get_info['prizeName'] = CfgTreasureBox::where('id',$is_get_info['treasure_box_id'])->value('prizeName');
        }else{
            $is_get_info['is_open'] = false;
        }

        $is_get_info['user']=User::where('id',$user_id)->field('id,nickname,avatarurl')->find();
        $is_get_info['treasure_box_times'] = UserExt::where('user_id', $this->uid)->value('treasure_box_times');

        Common::res(['data' => $is_get_info]);
    }

    /**
     * 打开宝箱
     */
    public function open ()
    {
        $this->getUser();
        $user_id = input('user_id');
        $index = input('index','0');
        if (!$user_id) Common::res(['code' => 100]);

        $res=UserTreasureBox::openBox($user_id,$this->uid,$index);
        Common::res(['data'=>$res]);
    }

    /**
     * 打开其他人宝箱，好友列表帮助开宝箱
     */
    public function openOther ()
    {
        $this->getUser();
        $user_id = input('user_id');
        if (!$user_id) Common::res(['code' => 100]);

        $res=UserTreasureBox::openOtherBox($user_id,$this->uid);
        Common::res(['data'=>$res]);
    }

    /**
     * 宝箱记录
     */
    public function log()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $logList = UserTreasureBox::getList($this->uid, $page, $size);

        Common::res(['data' => $logList]);
    }

    /**
     * 全服开箱排行榜
     */
    public function getOpenBoxRank()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $data['list'] = UserExt::with('User')->where('help_open_times','>',0)->field('id,user_id,help_open_times,update_time')->order('help_open_times desc,update_time asc')->page($page, $size)->select();
        $data['my_help_open_times'] = UserExt::where('user_id',$this->uid)->value('help_open_times');
        $data['my_help_open_rank'] = (UserExt::where('help_open_times','>',$data['my_help_open_times'])->order('help_open_times desc,update_time asc')->count())+1;
        if($page==1 && count($data['list'])==0){
            $data['my_help_open_rank'] = 0;
        }
        if($data['my_help_open_rank']>=10000){
            $data['my_help_open_rank'] = '999+';
        }

        Common::res(['data' => $data]);
    }

}
