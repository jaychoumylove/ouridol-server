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
    public static function getInfo($uid)
    {
        $item = self::get(['user_id' => $uid]);

        if (!$item) {
            self::create(['user_id' => $uid, 'settle_time' => time()]);
            $item = self::get(['user_id' => $uid]);
        }

        // 能量收益
        $duratime = time() - $item['settle_time'];
        $spriteLimitTime =  Cfg::getCfg('spriteLimitTime');
        if ($duratime / $spriteLimitTime >= 1) {
            $item['isFull'] = true;
            $duratime = $spriteLimitTime;
        }
        $earnPer = CfgSprite::where(['level' => $item['sprite_level']])->value('earn');
        // 每100秒收益
        $item['earnPer'] = $earnPer;

        $item['earn'] = floor($duratime / 100) * $earnPer;
        // 下一级所需灵丹
        $item['need_stone'] = CfgSprite::where(['level' => $item['sprite_level'] + 1])->value('need_stone');

        // if($uid == 1){
        //     // GM 收益始终显示为100
        //     $item['earn'] = 100;
        // }

        return $item;
    }

    /**结算该用户宠物收益 */
    public static function settle($uid, $self)
    {
        $userSprite = self::getInfo($uid);
        Db::startTrans();
        try {
            self::where(['user_id' => $uid])->update([
                'settle_time' => time() - 5,
            ]);

            if ($uid != $self) {
                // 他人帮收
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
        return $userSprite['earn'];
    }

    public static function upgrade($uid, $type)
    {
        $userSprite = self::getInfo($uid);
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
            ],[
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
