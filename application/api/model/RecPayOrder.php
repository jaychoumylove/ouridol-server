<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\api\service\User as UserService;

class RecPayOrder extends Base
{
    /**支付成功 处理业务 */
    public static function paySuccess($order)
    {
        // 礼物name
        $itemName = CfgItem::where(['id' => $order['item_id']])->value('name');
        if ($order['friend_uid'] != 0) {
            // 代充
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

        // 货币增加
        (new UserService())->change($order['user_id'], [
            'coin' => $order['coin'],
            'stone' => $order['stone'],
        ], $log);

        // 道具增加
        UserItem::addItem($order['user_id'], $order['item_id'], 1);
    }
}
