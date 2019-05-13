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
        $item = self::get(['user_id' => $uid, 'star_id' => $starid]);
        if ($item) {
            self::where(['user_id' => $uid, 'star_id' => $starid])->update([
                'total_count' => Db::raw('total_count+' . $hot),
                'thisweek_count' => Db::raw('thisweek_count+' . $hot),
                'thismonth_count' => Db::raw('thismonth_count+' . $hot),
                'thisday_count' => Db::raw('thisday_count+' . $hot),
            ]);
        } else {
            Common::res(['code' => 301]);
        }
    }

    /**加入爱豆圈子 */
    public static function joinNew($starid, $uid)
    {
        $item = self::get(['user_id' => $uid, 'star_id' => $starid]);
        if ($item) {
            Common::res(['code' => 302]);
        } else {
            self::create([
                'user_id' => $uid, 'star_id' => $starid
            ]);
        }
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
                Common::res(['code' => 400]);
            }
        } else {
            Common::res(['code' => 1, 'msg' => '退出偶像圈失败，上次退出偶像圈时间为' . date('Y-m-d', $ext['exit_group_time'])]);
        }
    }
}
