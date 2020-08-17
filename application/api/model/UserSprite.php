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
        //能量蛋等级存储时间及能量蛋图片
        $item['egg_info'] = CfgEgg::where('level', $item['egg_level'])->find();
        $item['next_egg_info'] = CfgEgg::where('level', $item['egg_level'] + 1)->find();
        $storage_time = CfgEgg::where('level', $item['egg_level'])->value('storage_time');
        if ($storage_time) {
            $spriteLimitTime = $storage_time * 3600;
        } else {
            $spriteLimitTime = Cfg::getCfg('spriteLimitTime');
        }

        if ($duratime >= $spriteLimitTime) {
            $item['isFull'] = true;
            $duratime = $spriteLimitTime;
        }
        $earnPer = CfgSprite::where(['level' => $item['sprite_level']])->value('earn');
        // 每100秒收益
        $item['earnPer'] = $earnPer;
        if ($duratime) {
            $item['earn'] = floor($duratime / 10) * $earnPer;
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

            //是否存在领能量双倍卡,先判断是否使用了一个了
            $isUseCard7 = UserProp::where(['user_id' => $uid, 'status' => 1, 'prop_id' => 7])->where('use_time', '<>', 0)->value('id');
            if ($isUseCard7) {
                $item['isExistCard7'] = -1;
            } else {
                $item['isExistCard7'] = UserProp::where(['user_id' => $uid, 'status' => 0, 'prop_id' => 7])->value('id');
            }
        }

        // if($uid == 1){
        //     // GM 收益始终显示为100
        //     $item['earn'] = 100;
        // }
        $item['sprite_img'] = CfgSprite::where(['level' => $item['sprite_level']])->value('image');
        $item['next_sprite_level'] = CfgSprite::where(['level' => $item['sprite_level']+1])->value('level');
        if($item['sprite_level']<5){
            $total_need_stone = CfgSprite::where('level','<',5)->where('level','>=',$item['sprite_level'])->sum('need_stone');
            $item['tips_text'] = '到lv.5还需'.$total_need_stone.'灵丹';
        }elseif ($item['sprite_level']<10){
            $total_need_stone = CfgSprite::where('level','<',10)->where('level','>=',$item['sprite_level'])->sum('need_stone');
            $item['tips_text'] = '到lv.10还需'.$total_need_stone.'灵丹';
        }


        return $item;
    }

    /**结算该用户宠物收益 */
    public static function settle($uid, $self)
    {
        $userSprite = self::getInfo($uid, $self);
        if ($userSprite['earn'] > 0) {
            Db::startTrans();
            try {

                // 使用道具卡
                $prop_id = 7;
                $isDone = UserProp::where([
                    'user_id' => $uid,
                    'prop_id' => $prop_id,
                ])->where('status', 1)->where('use_time', '<>', 0)->limit(1)->update(['use_time' => 0]);

                if ($isDone) {
                    // 双倍领取当次收益
                    $userSprite['earn'] = $userSprite['earn'] * 2;
                }

                self::where(['user_id' => $uid])->update([
                    'settle_time' => time() - 5,
                    'total_coin' => Db::raw('total_coin+' . $userSprite['earn'])
                ]);

                if ($uid != $self) {
                    // 他人帮收
                    Common::res(['code' => 1, 'msg' => '不能帮别人收取能量了']);
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

                UserSprite::where('user_id', $uid)->update([
                    'thisday_coin' => Db::raw('thisday_coin+' . $userSprite['earn']),
                ]);
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
                $type = 11;
                break;
            case 1:
                // 技能一升级
                $nextSkill = CfgSpriteSkillone::get(['level' => $userSprite['skillone_level'] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $field = 'skillone_level';
                $need_stone = $nextSkill['need_stone'];
                $type = 11;
                break;
            case 2:
                // 能量蛋升级
                $nextEgg = CfgEgg::get(['level' => $userSprite['egg_level'] + 1]);
                if (!$nextEgg) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $field = 'egg_level';
                $need_stone = $nextEgg['need_stone'];
                $type = 43;
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
                'type' => $type
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
        $myEarn = floor($earn * Cfg::getCfg('sprite_percent'));

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

    //每日产量排行榜
    public static function getRankList($uid, $page, $size)
    {

        if($page<=10){
            $list = self::with('User')->where('thisday_coin', '>', 0)->field('id,user_id,thisday_coin,lastday_coin')->order('thisday_coin desc,lastday_coin desc')
                ->page($page, $size)->select();
            foreach ($list as &$value) {
                $star_id = UserStar::where('user_id', $value['user_id'])->value('star_id');
                if($star_id){
                    $value['starname'] = Star::where('id', $star_id)->value('name');
                }
            }
        }else{
            $list = [];
        }

        $myInfo['thisday_coin'] = self::where('user_id',$uid)->value('thisday_coin');
        $myInfo['rank'] = (self::where('thisday_coin','>',$myInfo['thisday_coin'])->order('thisday_coin desc,lastday_coin desc')->count())+1;

        $banner = 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HUKRibxkbQUYy5TEicA6o19g9jcQNVibn3ZOQ3kXeCSEfsp1rWCyAW4nwTNbxZfKQqJvv3QucCVUCpQ/0';

        return ['list'=>$list,'myInfo'=>$myInfo,'banner'=>$banner];
    }

    /** 膜拜大神*/
    public static function zanGod($uid, $user_id, $earn_coin = 1000, $god_earn_coin = 10)
    {
        $myStar = UserStar::where('user_id', $uid)->value('star_id');
        $godStar = UserStar::where('user_id', $user_id)->value('star_id');
        if ($myStar != $godStar) Common::res(['code' => 1, 'msg' => '只能膜拜同一圈子的大神！']);
        Db::startTrans();
        try {
            $isDone = self::where('user_id', $uid)->where('god_count', '>', 0)->update([
                'god_count' => Db::raw('god_count-1'),
                'god_count_time' => time(),
            ]);
            if (!$isDone) Common::res(['code' => 1, 'msg' => '已经没有膜拜次数了，每天只能膜拜三次！']);
            self::where('user_id', $user_id)->update([
                'cover_god_count' => Db::raw('cover_god_count+1'),
            ]);

            (new User())->change($uid, [
                'coin' => $earn_coin,
            ], ['type' => 47, 'target_user_id' => $user_id]);
            (new User())->change($user_id, [
                'coin' => $god_earn_coin,
            ], ['type' => 48, 'target_user_id' => $uid]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
        return $cover_count = self::where('user_id', $user_id)->value('cover_god_count');
    }
}
