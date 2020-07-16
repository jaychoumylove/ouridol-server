<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;

class UserSprite extends Base
{
    public function CfgSprite()
    {
        return $this->belongsTo('CfgSprite', 'sprite_level', 'level');
    }

    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**获取该用户宠物信息 */
    public static function getInfo($uid, $self)
    {
        $item = self::get(['user_id' => $uid]);

        if (!$item) {
            self::create(['user_id' => $uid, 'settle_time' => time()]);
            $item = self::get(['user_id' => $uid]);
        }

        // 能量收益
        $duratime = time() - $item['settle_time'];
        //能量蛋等级存储时间
        $storage_time = CfgEgg::where('level',$item['egg_level'])->value('storage_time');
        if($storage_time){
            $spriteLimitTime =  $storage_time * 3600;
        }else{
            $spriteLimitTime =  Cfg::getCfg('spriteLimitTime');
        }

        if ($duratime >= $spriteLimitTime) {
            $item['isFull'] = true;
            $duratime = $spriteLimitTime;
        }
        $earnPer = CfgSprite::where(['level' => $item['sprite_level']])->value('earn');
        // 每100秒收益
        $item['earnPer'] = $earnPer;
        if ($duratime) {
            $item['earn'] = floor($duratime / 100) * $earnPer;
        } else {
            $item['earn'] = 0;
        }
        if ($uid == $self) {
            // 下一级所需灵丹
            $item['need_stone'] = CfgSprite::where(['level' => $item['sprite_level'] + 1])->value('need_stone');
            // 精灵生产加速卡
            $prop_id = 2;
            $item['isUseCard'] = UserProp::where(['user_id' => $uid, 'status' => 1, 'prop_id' => $prop_id])
                ->where('use_time', '>=', time() - 2 * 3600)->value('id') == true;
        }

        // if($uid == 1){
        //     // GM 收益始终显示为100
        //     $item['earn'] = 100;
        // }
        $item['sprite_img'] = CfgSprite::where(['level' => $item['sprite_level']])->value('image');

        return $item;
    }

    /**结算该用户宠物收益 */
    public static function settle($uid, $self)
    {
        $userSprite = self::getInfo($uid, $self);
        if ($userSprite['earn'] > 0) {
            Db::startTrans();
            try {
                self::where(['user_id' => $uid])->update([
                    'settle_time' => time() - 5,
                    'total_coin' => Db::raw('total_coin+' . $userSprite['earn'])
                ]);

                if ($uid != $self) {
                    // 他人帮收
                    $update_time = UserStar::where(['user_id' => $uid])->value('update_time');
                    if (time() - strtotime($update_time) > Cfg::getCfg('Inactive_days') * 3600 * 24) {
                        Common::res(['code' => 1, 'msg' => '好友已经很久没有打榜了，提醒TA一起为偶像打榜']);
                    }
                    $log = [
                        'type' => 7,
                        'target_user_id' => $self
                    ];
                } else {
                    $log = [
                        'type' => 9
                    ];
                }

                (new User())->change($uid, [
                    'coin' => $userSprite['earn'],
                ], $log);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400, 'data' => $e->getMessage()]);
            }
        }
        return $userSprite['earn'];
    }

    public static function upgrade($uid, $type)
    {
        $userSprite = self::getInfo($uid, $uid);
        switch ($type) {
            case 0:
                // 精灵升级
                if (!$userSprite['need_stone']) {
                    Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                }
                $field = 'sprite_level';
                $need_stone = $userSprite['need_stone'];
                break;
            case 1:
                // 技能一升级
                $nextSkill = CfgSpriteSkillone::get(['level' => $userSprite['skillone_level'] + 1]);
                if (!$nextSkill)  Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $field = 'skillone_level';
                $need_stone = $nextSkill['need_stone'];

                break;
            default:
                # code...
                break;
        }

        Db::startTrans();
        try {
            self::where(['user_id' => $uid])->update([
                $field => Db::raw($field . '+1'),
            ]);

            (new User())->change($uid, [
                'stone' => $need_stone / -1,
            ], [
                'type' => 11
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }

    /**帮别人收 获得利益 */
    public static function getTip($earn, $uid)
    {
        // $userSprite = self::getInfo($uid);
        // 技能一等级百分比
        // $myEarn = CfgSpriteSkillone::where(['times' => ['elt', $userSprite['skillone_times']]])->order('times desc')->value('earn');

        // 按被收人的50%
        $myEarn =  floor($earn * Cfg::getCfg('sprite_percent'));

        (new User)->change($uid, [
            'coin' => $myEarn,
        ], [
            'type' => 4,
        ]);

        // 帮被人收集次数+1
        self::where(['user_id' => $uid])->update(['skillone_times' => Db::raw('skillone_times+1')]);
        return $myEarn;
    }

    public static function getSkill($type)
    {
        switch ($type) {
            case 1:
                return CfgSpriteSkillone::all();
                break;

            default:
                # code...
                break;
        }
    }
}
