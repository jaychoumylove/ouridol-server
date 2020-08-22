<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class UserSpriteBg extends Base
{
    public static function getAll($uid)
    {
        $res['list'] = CfgSpriteBg::where('status','ON')->select();
        foreach ($res['list'] as &$value) {
            $value['have_it'] = 0;
            if($value['desc']){
                $value['desc'] = json_decode($value['desc'], true);
            }
            if ($value['type'] == 0) {
                $value['have_it'] = 1;
            } else {
                $sprite_bg = self::where('user_id', $uid)->where('sprite_bg_id', $value['id'])->find();
                if ($sprite_bg) {
                    $value['have_it'] = 1;
                }
            }
        }
        $res['sprite_bg_id'] = UserSprite::where('user_id', $uid)->value('sprite_bg_id');

        return $res;
    }

    public static function useIt($uid,$id)
    {
        self::checkIt($uid,$id,'use');

        Db::startTrans();
        try {

            UserSprite::where('user_id', $uid)->update([
                'sprite_bg_id' => $id
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

    public static function buyIt($uid,$id)
    {
        self::checkIt($uid,$id,'buy');

        $need_stone = CfgSpriteBg::where('id',$id)->value('stone');
        Db::startTrans();
        try {

            self::addSpriteBg($uid,$id);

            (new User())->change($uid, ['stone' => -$need_stone], ['type' => 50, 'content' => '购买精灵背景']);// 扣除灵丹并记录

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

    public static function unlockIt($uid,$id)
    {
        self::checkIt($uid,$id,'unlock');

        Db::startTrans();
        try {

            self::addSpriteBg($uid,$id);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

    public static function checkIt($uid,$id,$type){
        $check = CfgSpriteBg::get($id);
        if (!$check) Common::res(['code' => 1, 'msg' => '背景不存在']);

        switch ($type) {
            case 'use'://使用
                if ($check['status'] == 'OFF') Common::res(['code' => 1, 'msg' => '该背景已下架']);
                $exist = self::where('user_id', $uid)->where('sprite_bg_id', $id)->find();
                if ($check['type']!=0 && !$exist) Common::res(['code' => 1, 'msg' => '您还未拥有该背景']);

                break;
            case 'buy'://购买
                if ($check['type']!=1) Common::res(['code' => 1, 'msg' => '不能购买该背景']);
                if ($check['status'] == 'OFF') Common::res(['code' => 1, 'msg' => '该背景已下架']);

                $need_stone = CfgSpriteBg::where('id',$id)->value('stone');
                $userCurrencyStone = UserCurrency::where('uid',$uid)->value('stone');
                if ($need_stone > $userCurrencyStone) Common::res(['code' => 1, 'msg' => '灵丹不足']);

                break;
            case 'unlock'://解锁
                if ($check['type']!=2) Common::res(['code' => 1, 'msg' => '不能解锁该背景']);
                if ($check['status'] == 'OFF') Common::res(['code' => 1, 'msg' => '该背景已下架']);
                if ($check['start_time']>0 && $check['start_time']>time()) Common::res(['code' => 1, 'msg' => '该背景还未到开启时间']);
                if ($check['end_time']>0 && $check['end_time']<time()) Common::res(['code' => 1, 'msg' => '该背景已过限定时间']);

                if($id==9){
                    $invite_energy= UserExt::where(['user_id' => $uid])->value('invite_energy');
                    $sprite_info= UserSprite::where(['user_id' => $uid])->field('sprite_level,lastday_coin')->find();
                    $lastday_rank = (UserSprite::where('lastday_coin','>',$sprite_info['lastday_coin'])->order('lastday_coin desc,sprite_level desc')->count())+1;
                    if($invite_energy<15 && $sprite_info['sprite_level']<15 && $lastday_rank>100)Common::res(['code' => 1, 'msg' => '未满足解锁条件']);
                }

                break;
            default:
                # code...
                break;
        }
    }

    public static function addSpriteBg($uid,$id){

        $update = self::where('user_id', $uid)->where('sprite_bg_id', $id)->update([
            'sprite_bg_id'=>$id
        ]);
        if(!$update){
            self::create([
                'user_id' => $uid,
                'sprite_bg_id' => $id,
            ]);
        }
    }

    //上传背景头像
    public static function uploadImg($uid,$id,$avatar){

        $type = CfgSpriteBg::where('id',$id)->value('type');
        if($type!=2)Common::res(['code' => 1, 'msg' => '该背景不可上传头像']);

        Db::startTrans();
        try {

            self::where('user_id', $uid)->where('sprite_bg_id', $id)->update([
                'sprite_bg_upload_img'=>$avatar
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

    }

}
