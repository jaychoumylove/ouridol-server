<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User as UserService;

class UserFather extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'son', 'id')->field('id,nickname,avatarurl');
    }

    public function f()
    {
        return $this->belongsTo('User', 'father', 'id')->field('id,nickname,avatarurl');
    }

    public static function getFatherList($uid)
    {
        // self::initFather($uid);
        $res = self::with('User')->where(['father' => $uid])->order('cur_contribute desc,create_time desc')->select();

        if ($res) {
            foreach ($res as &$value) {
                $value['user_earn'] = floor($value['cur_contribute'] * Cfg::getCfg('father_earn_per'));
                $total_count = UserStar::where('user_id', $value['son'])->value('total_count');
                $value['level'] = CfgUserLevel::where('total', '<=', $total_count)->max('level');
            }
        }
        $data['list'] = $res;
        $data['earn'] = Rec::where(['type' => 5, 'user_id' => $uid])->whereTime('create_time', 'd')->sum('coin');
        $father_uid = self::where('son', $uid)->value('father');
        $data['father'] = User::where('id', $father_uid)->value('nickname');
        return $data;
    }

    /**结成师徒关系 */
    public static function joinIt($father, $son)
    {
        if ($father == $son) return '您太自恋了，不能拜自己为师，也不能收自己为徒';
        // 师傅必须已加入圈子
        $userStar = UserStar::where(['user_id' => $father])->value('star_id');
        if (!$userStar) return '师傅必须已加入圈子';

        // 师傅与徒弟需在同一个圈子
        if (UserStar::where(['user_id' => $father])->value('star_id') != UserStar::where(['user_id' => $son])->value('star_id')) {
            // Common::res(['msg' => '不是同一圈子']);
            return '师傅与徒弟需在同一个圈子';
        }
        // 不能是别人徒弟
        if (self::get(['son' => $son])) {
            // Common::res(['msg' => '已经是别人的徒弟了']);
            return '他已经拜师了';
        }
        // 师徒关系不能逆转
        if (self::get(['father' => $son, 'son' => $father])) {
            // Common::res(['msg' => '你是邀请人的师傅，不能成为他的徒弟']);
            return '你们已经是师徒关系';
        }
        // 徒弟必须是师傅拉进来的
        // if (!UserRelation::get(['rer_user_id' => $father, 'ral_user_id' => $son])) {
        //     return;
        // }
        $isDone = UserStar::where('user_id',$son)->update([
            'is_son'=>1
        ]);
        if($isDone){
            self::create([
                'father' => $father,
                'son' => $son,
            ]);
        }
    }

    public static function sonEarn($father, $son)
    {
        $cur_contribute = (new UserFather())->readMaster()->where(['father' => $father, 'son' => $son])->value('cur_contribute');
        $earn = floor($cur_contribute * Cfg::getCfg('father_earn_per'));
        if ($earn) {
            // 收益
            Db::startTrans();
            try {
                $isDone = self::where(['father' => $father, 'son' => $son])->update([
                    // 实时贡献清零
                    'cur_contribute' => 0,
                ]);
                if (!$isDone) Common::res(['code' => 1, 'msg' => '请稍后再试']);
                self::where(['father' => $father, 'son' => $son])->update([
                    'has_earn_count' => Db::raw('has_earn_count+' . $earn),
                ]);

                UserExt::where(['user_id' => $father])->update([
                    'father_get_count' => Db::raw('father_get_count+' . $earn),
                    'father_get_time' => time(),
                ]);

                (new UserService())->change($father, [
                    'coin' => $earn
                ], [
                    'type' => 5,
                    'target_user_id' => $son
                ]);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400, 'data' => $e->getMessage()]);
            }

            return $earn;
        } else {
            Common::res(['code' => 1]);
        }
    }

    public static function initFather($uid)
    {
        $opTime = self::where(['father' => $uid])->max('update_time');
        $opTime = date('Ymd', strtotime($opTime));

        if (date('Ymd', time()) != $opTime) {
            // 重置
            self::where(['father' => $uid])->update([
                'cur_contribute' => 0,
                'has_earn_count' => 0
            ]);
        }
    }

    /**脱离师傅 */
    public static function breakFather($uid)
    {
        $isDone = self::destroy([
            'son' => $uid,
        ], true);

        if($isDone){
            UserStar::where('user_id',$uid)->update([
                'is_son'=>0
            ]);
        }else{
            Common::res(['code' => 1]);
        }
    }

    /**增加徒弟贡献 */
    public static function addContribute($son, $hot)
    {
        $opTime = self::where(['son' => $son])->value('update_time');
        if ($opTime) {
            if (date('Ymd', strtotime($opTime)) != date('Ymd')) {
                self::where(['son' => $son])->update([
                    'cur_contribute' => $hot,
                ]);
            } else {
                self::where(['son' => $son])->update([
                    'cur_contribute' => Db::raw('cur_contribute+' . $hot)
                ]);
            }
        }
    }

    /**
     * 接触师徒关系
     * @param $relation
     */
    public static function breakRelationship ($relation)
    {
        self::destroy($relation, true);
    }
}
