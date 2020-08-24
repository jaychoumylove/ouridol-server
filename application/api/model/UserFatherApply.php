<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class UserFatherApply extends Base
{

    public static function getList($uid, $type, $page, $size)
    {
        if($type==1){
            $list = self::where('father',$uid)->where('apply_user','<>',$uid)->where('status',0)->page($page, $size)->select();
            foreach ($list as &$value) {
                $value['user'] = User::where('id',  $value['son'])->field('id,nickname,avatarurl')->find();
                $value['count'] = UserStar::where('user_id',  $value['son'])->value('thisday_count');
            }
        }else{
            $list = self::where('son',$uid)->where('apply_user','<>',$uid)->where('status',0)->page($page, $size)->select();
            foreach ($list as &$value) {
                $value['user'] = User::where('id',  $value['father'])->field('id,nickname,avatarurl')->find();
                $value['count'] = UserExt::where('user_id',  $value['father'])->value('father_get_count');
            }
        }

        return $list;

    }

    /**申请结成师徒关系 */
    public static function apply($father, $son, $uid)
    {
        if ($father == $son) return '您太自恋了，不能拜自己为师，也不能收自己为徒';
        // 师傅必须已加入圈子
        $userStar = UserStar::where(['user_id' => $father])->value('star_id');
        if (!$userStar) return '师傅必须已加入圈子';

        // 师傅与徒弟需在同一个圈子
        if (UserStar::where(['user_id' => $father])->value('star_id') != UserStar::where(['user_id' => $son])->value('star_id')) {
            return '师傅与徒弟需在同一个圈子';
        }
        // 师徒关系不能逆转
        if ((new UserFather)->readMaster()->get(['father' => $son, 'son' => $father])) {
            return '你们已经是师徒关系';
        }
        // 不能是别人徒弟
        if ((new UserFather)->readMaster()->get(['son' => $son])) {
            return '他已经拜师了';
        }

        // 师徒关系不能再申请
        if ((new UserFatherApply())->readMaster()->get(['father' => $father, 'son' => $son , 'status' => 0])) {
            return '您已经申请过了';
        }

        $isDone = self::where(['father' => $father, 'son' => $son])->update(['status' => 0]);
        if(!$isDone){
            self::create([
                'father' => $father,
                'son' => $son,
                'apply_user' => $uid,
            ]);
        }

    }

    public static function applydeal($uid, $id, $status)
    {

        $info = self::get($id);
        if($info['apply_user']==$uid)Common::res(['msg' => '请等待对方同意', 'code' => 1]);

        Db::startTrans();
        try {

            if ($status == -1) {// 拒绝
                self::where('id', $id)->update([
                    'status' => $status
                ]);
            } elseif ($status == 1) { // 允许
                if ($info['father'] == $uid) {
                    self::where('father', $uid)->delete();
                    $res = UserFather::joinIt($uid, $info['son']);
                }elseif ($info['son'] == $uid) {
                    self::where('son', $uid)->delete();
                    $res = UserFather::joinIt($info['father'], $uid);
                }
                if($res){
                    Common::res(['msg' => $res, 'code' => 1]);
                }
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }

    }

}
