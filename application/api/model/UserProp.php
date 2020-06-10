<?php

namespace app\api\model;

use think\Cache;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;

class UserProp extends Base
{
    const DOUBLE_STEAL_CARD = 2;
    const TRIPLE_STEAL_CARD = 3;

    public function Prop()
    {
        return $this->belongsTo('Prop', 'prop_id', 'id');
    }

    /**
     * 增加道具
     * @param int $prop_id 
     * @param int $num 增加数量 
     */
    public static function addProp($user_id, $prop_id, $num)
    {
        $insert = [];
        for ($i = 0; $i < $num; $i++) {
            $insert[] = [
                'user_id' => $user_id,
                'prop_id' => $prop_id,
            ];
        }

        self::insertAll($insert);
    }

    public static function getList($uid)
    {
        $list = self::with('Prop')->where('user_id', $uid)->order('id desc')->select();

        // 检查是否已过期
        foreach ($list as &$value) {
            if ($value['status'] == 0 && date('Ymd', strtotime($value['create_time'])) != date('Ymd')) {
                // 购买的道具仅限当天使用
                $value['status'] = 2;
                self::where('id', $value['id'])->update(['status' => 2]);
            }
        }

        return $list;
    }

    /**使用道具 */
    public static function use($id)
    {
        $prop = self::get($id);
        if (!$prop || $prop['status'] != 0) Common::res(['code' => 3, 'msg' => '道具无法使用']);

        $res = [];
        Db::startTrans();
        try {
            switch ($prop['prop_id']) {
                case 1:
                    // 可偷能量增加卡

                    break;
                case 2:
                    // 精灵生产加速卡
                    // 是否有正在使用中的卡
                    $usingTime = 2 * 3600;

                    // 最近使用该类型道具时间
                    $least_use_time = self::where(['user_id' => $prop['user_id'], 'prop_id' => $prop['prop_id']])->max('use_time');
                    if ($least_use_time > time() - $usingTime) {
                        Common::res(['code' => 1, 'msg' => '抱歉，无法叠加']);
                    }

                    break;
                case 3:
                    // 送礼物获得额外能量卡
                    break;
                case 4:
                    // 能量福袋
                    $awards = [10000, 12000, 15000, 20000];
                    $rd = mt_rand(0, 3);
                    (new User())->change($prop['user_id'], [
                        'coin' => $awards[$rd]
                    ], ['type' => 26]);
                    $res['awards'] = $awards[$rd];
                case 5:
                    $expireTime = self::useMultipleStealCard($prop['user_id'], self::DOUBLE_STEAL_CARD);
                    $res['expire_time'] = date('Y-m-d H:i:s', $expireTime);
                    break;
                case 6:
                    $expireTime = self::useMultipleStealCard($prop['user_id'], self::TRIPLE_STEAL_CARD);
                    $res['expire_time'] = date('Y-m-d H:i:s', $expireTime);
                    break;
                default:
                    # code...
                    break;
            }

            self::where('id', $id)->update([
                'status' => 1,
                'use_time' => time()
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        return $res;
    }

    /**
     * 获取用户使用多倍卡的缓存key
     * @param $userId
     * @return string
     */
    public static function generateMultipleStealCardKey ($userId)
    {
        return sprintf("steal_card_%s", $userId);
    }

    /**
     * 创建多倍卡的缓存
     * @param $userId
     * @param $multiple
     * @return int|string
     */
    public static function useMultipleStealCard ($userId, $multiple)
    {
        $exist = self::checkMultipleStealCard($userId);
        if ($exist) Common::res(['code' => 1, 'msg' => '抱歉，无法叠加']);

        $stealCard = [
            self::DOUBLE_STEAL_CARD => [
                'multiple'     => self::DOUBLE_STEAL_CARD,
                'cooling_time' => 40
            ],
            self::TRIPLE_STEAL_CARD => [
                'multiple'     => self::TRIPLE_STEAL_CARD,
                'cooling_time' => 10
            ]
        ];

        $value = $stealCard[$multiple];

        $expireTime = bcmul(60, 60);

        $value['expire_time'] = time() + $expireTime; // 一小时后过期

        Cache::set(self::generateMultipleStealCardKey($userId), $value, $expireTime);

        return $value['expire_time'];
    }

    /**
     * 获取多倍卡参数
     * @param             $userId
     * @param string|null $var
     * @param bool        $default
     * @return bool|mixed
     */
    public static function getMultipleStealCardVar ($userId, $var = null, $default = false)
    {
        if (empty($userId)) return $default;

        $value = self::checkMultipleStealCard($userId);
        if (empty($value)) return $default;

        if (empty($var)) return $value;

        return array_key_exists($var, $value) ? $value[$var]: $default;
    }

    /**
     * 检查用户是否有多倍卡的使用
     * @param $userId
     * @return bool|mixed
     */
    public static function checkMultipleStealCard ($userId)
    {
        if (empty($userId)) return false;

        $key = self::generateMultipleStealCardKey($userId);

        $value = Cache::get($key);
        if (empty($value)) return false;

        return $value;
    }
}
