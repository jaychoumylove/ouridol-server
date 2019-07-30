<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\base\service\Common;

class PayGoods extends Base
{
    //

    public function Item()
    {
        return $this->belongsTo('CfgItem', 'item_id', 'id');
    }

    /**
     * 生成商品信息
     * @param int $type 0礼物 1道具
     */
    public static function getInfo($uid, $id, $num, $type)
    {
        if ($type == 0) {// 礼物
            $goods = self::get($id);

            // 活动： 爱豆当天生日 购买的礼物翻倍 灵丹不翻倍
            $star_id = UserStar::getStarId($uid);
            $birthday = Star::where('id', $star_id)->value('birthday');
            $goods['item_num'] = $birthday == date('md') ? 2 : 1;
        } else if ($type == 1) {// 道具
            $goods = Prop::get($id);
            if($goods['remain'] < $num) Common::res(['code' => 1, 'msg' => '库存不足']);
        }
        if (!$goods) Common::res(['code' => 303]);
        $goods['num'] = $num;
        $goods['type'] = $type;

        return $goods;
    }
}
