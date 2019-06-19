<?php
namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\api\service\User;
use app\base\service\Common;

class UserItem extends Base
{
    public static function getItem($uid)
    {
        return self::get(['uid' => $uid]);
    }


    public static function recharge($uid, $item_id, $num)
    {
        Db::startTrans();
        try {
            $itemCount = CfgItem::where('id', $item_id)->value('count');
            self::where(['uid' => $uid, 'item_id' => $item_id])->update([
                'count' => Db::raw('count-' . $num)
            ]);

            (new User())->change($uid, [
                'coin' => $num * $itemCount
            ], [
                'type' => 16
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }
}
