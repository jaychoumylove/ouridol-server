<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;

class UserStar extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    /**用户贡献排名 */
    public static function getRank($starid, $field, $page, $size)
    {
        if ($starid) {
            $w = ['star_id' => $starid];
        } else {
            $w = false;
        }

        return self::with('User')->where($w)->where([$field => ['neq', 0]])->order($field . ' desc')->page($page, $size)->select();
    }

    /**贡献度改变 */
    public static function change($uid, $starid, $hot)
    {
        self::where(['user_id' => $uid, 'star_id' => $starid])->update([
            'total_count' => Db::raw('total_count+' . $hot),
            'thisday_count' => Db::raw('thisday_count+' . $hot),
            'thisweek_count' => Db::raw('thisweek_count+' . $hot),
            'thismonth_count' => Db::raw('thismonth_count+' . $hot),
        ]);
    }

    /**加入爱豆圈子 */
    public static function joinNew($starid, $uid)
    {
        Db::startTrans();
        try {
            $userType = User::where('id', $uid)->value('type');
            if ($userType == 1) {
                // 管理员
                $uid = self::getVirtualUser($starid, $uid);
            }

            if (!self::get(['user_id' => $uid, 'star_id' => $starid])) {
                // Common::res(['code' => 302]);
                self::create([
                    'user_id' => $uid, 'star_id' => $starid
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
        return $uid;
    }

    /**
     * 获取该管理员在该圈子的虚拟用户
     * @param mixed $uid 管理员uid
     * @return mixed 虚拟用户uid
     */
    public static function getVirtualUser($starid, $uid)
    {
        $user = User::where('id', $uid)->find();
        if (strpos($user['openid'], '@') !== false) {
            Common::res(['code' => 1, 'msg' => '请尝试清除缓存后再试']);
        }
        $oldStarid = UserStar::where('user_id', $uid)->value('star_id');
        if (!$oldStarid) $oldStarid = 0;
        // 旧 带上oldStarid后缀
        User::where('id', $uid)->update([
            'openid' => $user['openid'] . '@' . $oldStarid,
            'unionid' => $user['unionid'] . '@' . $oldStarid
        ]);

        // 新的角色
        $virtualUid = User::where(['openid' => $user['openid'] . '@' . $starid])->value('id');
        if (!$virtualUid) {
            $virtualUid = User::createVirtualUser([
                'openid' => $user['openid'],
                'unionid' => $user['unionid'],
            ]);
        } else {
            User::where(['openid' => $user['openid'] . '@' . $starid])->update([
                'openid' => $user['openid'],
                'unionid' => $user['unionid']
            ]);
        }

        return $virtualUid;
    }

    /**退出偶像圈 */
    public static function exit($uid)
    {
        $ext = UserExt::get(['user_id' => $uid]);
        if (time() - $ext['exit_group_time'] > 3600 * 24 * 365 / 2) {
            // 半年才能退一次
            Db::startTrans();
            try {
                // 退圈
                self::destroy(['user_id' => $uid], true);
                // 记录退圈时间
                UserExt::where(['user_id' => $uid])->update(['exit_group_time' => time()]);
                // 清除师徒关系
                UserFather::where(['father' => $uid])->whereOr(['son' => $uid])->delete(true);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400, 'data' => $e->getMessage()]);
            }
        } else {
            Common::res(['code' => 1, 'msg' => '退出偶像圈失败，上次退出偶像圈时间为' . date('Y-m-d', $ext['exit_group_time'])]);
        }
    }

    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $starid, $field)
    {
        $res['score'] = self::where(['user_id' => $uid, 'star_id' => $starid])->value($field);
        if($res['score']) {
            $res['rank'] = self::where('star_id', $starid)->where($field, '>', $res['score'])->count() + 1;
        } else {
            $res['rank'] = '未上榜';
        }

        return $res;
    }
}
