<?php

namespace app\api\controller\v1;

use app\api\model\CfgTreasureBox;
use app\api\model\UserExt;
use app\api\model\UserTreasureBox;
use app\base\controller\Base;
use app\base\service\Common;

class TreasureBox extends Base
{

    protected $box_notice_id = 2;
    /**
     * 宝箱信息
     */
    public function info ()
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
     * 打开宝箱
     */
    public function open ()
    {
        $this->getUser();
        $user_id = input('user_id');
        $index = input('index');
        if (!$user_id) Common::res(['code' => 100]);

        $res=UserTreasureBox::openBox($user_id,$this->uid,$index);
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
}
