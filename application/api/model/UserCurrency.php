<?php

namespace app\api\model;

use app\base\model\Base;
use app\api\service\User as UserService;
use app\base\service\Common;
use think\Db;

class UserCurrency extends Base
{
    public static function getCurrency($uid)
    {
        $item = self::get(['uid' => $uid]);
        if (!$item) {
            $item = self::create([
                'uid' => $uid,
                'coin' => 0,
                'stone' => 0,
                'trumpet' => 0,
            ]);
        }
        $item['item_count'] = UserItem::where(['uid' => $uid])->sum('count');
        unset($item['id']);
        unset($item['create_time']);
        unset($item['uid']);
        return $item;
    }

    /**送灵丹给他人 */
    public static function sendStoneToOther($self, $other, $num, $type)
    {
        if (!$self || !$other || $self == $other) Common::res(['code' => 1, 'msg' => '操作失败']);
        Db::startTrans();
        try {
            $userService = new UserService();

            $userService->change($self, [
                $type => -$num
            ], [
                'type' => 18,
                'content' => json_encode([User::where('id', $other)->value('nickname')], JSON_UNESCAPED_UNICODE)
            ]);

            $userService->change($other, [
                $type => $num
            ], [
                'type' => 19,
                'content' => json_encode([User::where('id', $self)->value('nickname')], JSON_UNESCAPED_UNICODE)
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }
}
