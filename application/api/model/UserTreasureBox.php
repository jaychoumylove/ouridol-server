<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class UserTreasureBox extends Base
{

    public static function checkTime(){

        $time1 = date('Y-m-d').'00';
        $time2 = date('Y-m-d').'11';
        $time3 = date('Y-m-d').'18';
        if(date('Y-m-d H')>$time1 && date('Y-m-d H')<$time2){
            $date = $time1;
        }elseif (date('Y-m-d H')>$time2 && date('Y-m-d H')<$time3){
            $date = $time2;
        }else{
            $date = $time3;
        }
        return $date;
    }
}
