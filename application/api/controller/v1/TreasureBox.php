<?php

namespace app\api\controller\v1;

use app\api\model\CfgTreasureBox;
use app\api\model\UserExt;
use app\api\model\UserTreasureBox;
use app\base\controller\Base;
use app\base\service\Common;

class TreasureBox extends Base
{

    /**
     * 宝箱信息
     */
    public function info ()
    {
        $this->getUser();
//        $res['flip_cards_count'] = (new UserExt())->where('user_id', $this->uid)->value('flip_cards_count');
        $res['list'] = [[],[],[],[],[],[]];

        foreach ($res['list'] as $key => $value) {

            $create_date_hour = UserTreasureBox::checkTime();
            $is_get_info = UserTreasureBox::where('user_id', $this->uid)->where('index', $key)->where('create_date_hour',$create_date_hour)->find();
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

    }

    /**
     * 宝箱记录
     */
    public function log ()
    {

    }
}
