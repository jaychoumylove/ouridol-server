<?php

namespace app\api\model;

use app\api\controller\v1\Ext;
use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class UserHeadwear extends Base
{
    /**正在使用的头饰 */
    public static function getUse($uid)
    {
        $hid = self::where('user_id', $uid)->where('end_time is NULL or end_time>="' . date('Y-m-d H:i:s') . '"')->where('status', 1)->value('headwear_id');
        return CfgHeadwear::get($hid);
    }

    public static function cancel($uid)
    {
        self::where('user_id', $uid)->update(['status' => 0]);
    }

    public static function useIt($uid, $id)
    {
        self::checkIt($uid, $id, 'use');

        Db::startTrans();
        try {

            self::cancel($uid);
            self::where('user_id', $uid)->where('headwear_id', $id)->update(['status' => 1]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

    public static function buyIt($uid, $id)
    {
        self::checkIt($uid, $id, 'buy');

        $headWear = CfgHeadwear::where('id', $id)->find();

        Db::startTrans();
        try {

            self::create([
                'user_id' => $uid,
                'headwear_id' => $id,
                'end_time' => $headWear['days'] > 0 ? date('Y-m-d H:i:s', strtotime('+' . $headWear['days'] . ' day')) : NULL
            ]);

            (new User())->change($uid, ['stone' => -$headWear['need_stone']], ['type' => 53, 'content' => '购买头饰']);// 扣除灵丹并记录

            self::useIt($uid, $id);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

    public static function unlockIt($uid, $id)
    {
        self::checkIt($uid, $id, 'unlock');

        $headWear = CfgHeadwear::where('id', $id)->find();
        Db::startTrans();
        try {

            self::create([
                'user_id' => $uid,
                'headwear_id' => $id,
                'end_time' => $headWear['days'] > 0 ? date('Y-m-d H:i:s', strtotime('+' . $headWear['days'] . ' day')) : NULL
            ]);

            self::useIt($uid, $id);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

    public static function checkIt($uid, $id, $type)
    {
        $check = CfgHeadwear::get($id);
        if (!$check) Common::res(['code' => 1, 'msg' => '头饰不存在']);

        switch ($type) {
            case 'use'://使用
                $exist = self::where('user_id', $uid)->where('headwear_id', $id)->find();
                if ($check['type'] != 0 && !$exist) Common::res(['code' => 1, 'msg' => '您还未拥有该头饰']);

                break;
            case 'buy'://购买
                if ($check['type'] != 1) Common::res(['code' => 1, 'msg' => '不能购买该头饰']);

                $userCurrencyStone = UserCurrency::where('uid', $uid)->value('stone');
                if ($check['need_stone'] > $userCurrencyStone) Common::res(['code' => 1, 'msg' => '灵丹不足']);

                break;
            case 'unlock'://解锁
                if ($check['type'] != 2) Common::res(['code' => 1, 'msg' => '不能解锁该头饰']);


                if($id<=21){
                    self::teacherActive($id,$uid);
                } else {
                    Common::res(['code' => 1, 'msg' => '不能解锁该头饰']);
                }

                break;
            default:
                # code...
                break;
        }
    }

    public static function teacherActive($id,$uid)
    {

        if(!Ext::is_start('is_teacher_active')) Common::res(['code' => 1, 'msg' => '活动已结束']);

        if ($id == 1) {
            //1、师傅每日收益排行前三的用户获得【最美师父】绝版头饰
            $lastday_father_get_count = UserExt::where(['user_id' => $uid])->value('lastday_father_get_count');
            $lastday_rank = (UserExt::where('lastday_father_get_count', '>', $lastday_father_get_count)->order('lastday_father_get_count desc,father_get_time desc')->count()) + 1;
            if ($lastday_father_get_count==0 || $lastday_rank > 3) Common::res(['code' => 1, 'msg' => '未满足解锁条件']);
        } else if ($id == 2) {
            //2、精灵产量收益排行榜前十的用户获得【大神】绝版头饰
            $lastday_coin = UserSprite::where(['user_id' => $uid])->value('lastday_coin');
            $lastday_rank = (UserSprite::where('lastday_coin', '>', $lastday_coin)->order('lastday_coin desc,sprite_level desc')->count()) + 1;
            if ($lastday_coin==0 || $lastday_rank > 10) Common::res(['code' => 1, 'msg' => '未满足解锁条件']);
        } else {
            $need_count = CfgHeadwear::where(['id' => $id])->value('need_stone');//在活动里该字段可任意代表其他数量
            $thisweek_count = UserStar::where(['user_id' => $uid])->value('thisweek_count');
            if ($need_count > $thisweek_count) Common::res(['code' => 1, 'msg' => '未满足解锁条件，周贡献需达到'.$need_count]);
        }
    }

}
