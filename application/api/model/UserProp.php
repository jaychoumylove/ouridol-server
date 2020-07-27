<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;

class UserProp extends Base
{
    const DOUBLE_STEAL_CARD_ID = 5;
    const TRIPLE_STEAL_CARD_ID = 6;
    const DOUBLE_STEAL_CARD = 2;
    const TRIPLE_STEAL_CARD = 3;

    public static $stealCard = [
        self::DOUBLE_STEAL_CARD_ID => [
            'multiple'     => self::DOUBLE_STEAL_CARD,
            'cooling_time' => 40
        ],
        self::TRIPLE_STEAL_CARD_ID => [
            'multiple'     => self::TRIPLE_STEAL_CARD,
            'cooling_time' => 10
        ]
    ];

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

    public static function getList($uid, $page, $size)
    {
        $list = self::with('Prop')->where('user_id', $uid)->order('id desc')->whereTime('create_time', '>', '-3 days')->page ($page, $size)->select();

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
                    break;
                case self::DOUBLE_STEAL_CARD_ID:
                    $expireTime = self::useMultipleStealCard($prop['user_id'], self::DOUBLE_STEAL_CARD_ID);
                    $res['expire_time'] = date('Y-m-d H:i:s', $expireTime);
                    break;
                case self::TRIPLE_STEAL_CARD_ID:
                    $expireTime = self::useMultipleStealCard($prop['user_id'], self::TRIPLE_STEAL_CARD_ID);
                    $res['expire_time'] = date('Y-m-d H:i:s', $expireTime);
                    break;
                case 7:
                    // 领能量双倍卡
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
     * 使用灵丹兑换道具
     * @param $uid
     * @param $propId
     * @param $num
     * @throws \think\exception\DbException
     */
    public static function exchange ($uid, $propId, $num)
    {
        $userCurrency = UserCurrency::get(['uid' => $uid]);
        if (empty($userCurrency)) Common::res(['code' => 1, 'msg' => '不存在的用户']);

        $prop = Prop::get($propId);
        if (empty($prop)) Common::res(['code' => 1, 'msg' => "未知道具"]);
        if ($prop['status'] == Prop::OFF) Common::res(['code' => 1, 'msg' => '道具已下架']);

        if ((int) $prop['stone'] < 1) Common::res(['code' => 1, 'msg' => '道具已禁止兑换']);
        if ((int) $prop['remain'] < $num) Common::res(['code' => 1, 'msg' => sprintf('%s仅剩%s个', $prop['name'], $prop['remain'])]);

        // 所花费的砖石
        $tokenStone = bcmul($prop['stone'], $num);
        if ($tokenStone > $userCurrency['stone']) Common::res(['code' => 1, 'msg' => '灵丹不足']);

        self::addProp($uid, $propId, $num);

        Prop::where(['id' => $propId])->update(['remain' => bcsub($prop['remain'], $num)]);

        $recordContent = sprintf('["%s", "%s"]', $num, $prop['name']);

        (new User())->change($uid, ['stone' => -$tokenStone], ['type' => 39, 'content' => $recordContent]);// 扣除灵丹并记录
    }

    /**
     * 创建多倍卡的缓存
     * @param $userId
     * @param $multiple
     * @return int|string
     * @throws \think\exception\DbException
     */
    public static function useMultipleStealCard ($userId, $multiple)
    {
        $exist = self::checkMultipleStealCard($userId);
        if ($exist) Common::res(['code' => 1, 'msg' => '抱歉，无法叠加']);

        $value = self::$stealCard[$multiple];

        $expireTime = bcmul(60, 60);

        $value['expire_time'] = time() + $expireTime; // 一小时后过期

        return $value['expire_time'];
    }

    /**
     * 获取多倍卡参数
     * @param             $userId
     * @param string|null $var
     * @param bool        $default
     * @return bool|mixed
     * @throws \think\exception\DbException
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
     * @throws \think\exception\DbException
     */
    public static function checkMultipleStealCard ($userId)
    {
        if (empty($userId)) return false;

        $time = bcsub(time(), 3600);

        $propIds = [self::DOUBLE_STEAL_CARD_ID, self::TRIPLE_STEAL_CARD_ID];

        $where = sprintf('`user_id` = %s and `use_time` > %s and `prop_id` in (%s)',
            $userId,
            $time,
            implode(',', $propIds)
        );

        $exist = self::get(function ($query) use ($where) {
            $query->where($where);
        });

        if (empty($exist)) return false;

        return self::$stealCard[$exist['prop_id']];
    }
}
