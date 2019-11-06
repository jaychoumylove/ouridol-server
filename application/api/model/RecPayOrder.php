<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\api\service\User as UserService;
use think\Db;

class RecPayOrder extends Base
{
    /**支付成功 处理业务 */
    public static function paySuccess($order)
    {
        $goodsInfo = json_decode($order['goods_info'], true);
        if ($goodsInfo['type'] == 0) {
            self::buyItem($goodsInfo, $order);
        } else if ($goodsInfo['type'] == 1) {
            self::buyProp($goodsInfo, $order);
        }
    }

    public static function buyItem($goodsInfo, $order)
    {
        // 礼物name
        $itemName = CfgItem::where(['id' => $goodsInfo['item_id']])->value('name');
        if ($order['friend_uid'] != 0) {
            // 代充
            // 给自己记录的日志
            Rec::create([
                'user_id' => $order['user_id'],
                'type' => 22,
                'content' => json_encode([
                    User::where('id', $order['friend_uid'])->value('nickname'),
                    $itemName
                ], JSON_UNESCAPED_UNICODE)
            ]);

            $log = [
                'type' => 23,
                'content' => json_encode([
                    User::where('id', $order['user_id'])->value('nickname'),
                    $itemName
                ], JSON_UNESCAPED_UNICODE)
            ];
            $order['user_id'] = $order['friend_uid'];
        } else {
            // 自充
            $log = [
                'type' => 8,
                'content' => json_encode([$itemName], JSON_UNESCAPED_UNICODE)
            ];
        }

        if ($goodsInfo['coin'] != 0) { 
            // 货币增加
            (new UserService())->change($order['user_id'], [
                'coin' => $goodsInfo['coin'],
                'stone' => $goodsInfo['stone'],
            ]);
            Rec::addRec([
                'user_id' => $order['user_id'],
                'type' => $log['type'],
                'content' => $log['content'],
                'stone' => $goodsInfo['stone'],
            ]);
            Rec::addRec([
                'user_id' => $order['user_id'],
                'type' => 31,
                'coin' => $goodsInfo['coin'],
            ]);
        } else {
            // 货币增加
            (new UserService())->change($order['user_id'], [
                'coin' => $goodsInfo['coin'],
                'stone' => $goodsInfo['stone'],
            ], $log);
        }

        // 礼物增加
        UserItem::addItem($order['user_id'], $goodsInfo['item_id'], $goodsInfo['item_num']);
    }

    public static function buyProp($goodsInfo, $order)
    {
        // 购买的道具
        UserProp::addProp($order['user_id'], $goodsInfo['id'], $goodsInfo['num']);
        // 剩余扣除
        Prop::where('id', $goodsInfo['id'])->update([
            'remain' => Db::raw('remain-' . $goodsInfo['num'])
        ]);
        // 日志
        Rec::create([
            'user_id' => $order['user_id'],
            'type' => 25,
            'content' => json_encode([
                Prop::where('id', $goodsInfo['id'])->value('name'),
            ], JSON_UNESCAPED_UNICODE)
        ]);
    }
}
