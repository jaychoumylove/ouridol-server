<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class CfgHeadwear extends Base
{
    //
    public static function getAll($uid)
    {
        //删除过期的头饰
        UserHeadwear::where('user_id', $uid)->where('end_time', '<', date('Y-m-d H:i:s'))->delete();
        $myHeadwearHas = UserHeadwear::where('user_id', $uid)->where('status', 0)->column('headwear_id');
        $myHeadwearUse = UserHeadwear::where('user_id', $uid)->where('status', 1)->column('headwear_id');
        $list = self::order('sort desc,need_stone asc')->select();

        foreach ($list as $key => &$value) {
            $value['status'] = -1;
            if (in_array($value['id'], $myHeadwearHas)) {
                $value['status'] = 0;
            }
            if (in_array($value['id'], $myHeadwearUse)) {
                $value['status'] = 1;
                // 正在佩戴的头饰
                $res['cur'] = $value;
            }
            if($value['desc']){
                $value['desc'] = json_decode($value['desc'], true);
            }
        }
        $res['list'] = $list;

        return $res;
    }
}
