<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\PayGoods;
use app\api\model\RecPayOrder;
use app\base\service\WxAPI;
use app\api\model\User;
use app\base\service\WxPay as WxPayService;
use app\api\service\User as UserService;
use think\Db;
use app\api\model\UserItem;
use app\api\model\CfgItem;
use app\api\model\Rec;
use app\api\model\Prop;
use app\api\model\UserStar;
use app\api\model\Star;
use app\api\model\Cfg;

class Payment extends Base
{
    public function goods()
    {
        $res['list'] = PayGoods::with('Item')->where('1=1')->select();

        // 明星生日福利
        $this->getUser();
        $star_id = UserStar::getStarId($this->uid);
        $birthday = Star::where('id', $star_id)->value('birthday');
        $res['item_double'] = $birthday == date('md');

        Common::res(['data' => $res]);
    }

    /**
     * 下单
     * @只能有一个商品，但可以多数量
     */
    public function order()
    {
        $this->getUser();
        $goodsId = input('goods_id');// 商品id
        $goodsNum = input('goods_num', 1);// 商品数量
        $user_id = input('user_id', 0); // 代充值uid
        $type = input('type', 0); // 购买类型
        if ($user_id == $this->uid) $user_id = 0;
        if (!$goodsId) Common::res(['code' => 100]);

        // 商品
        $goods = PayGoods::getInfo($this->uid, $goodsId, $goodsNum, $type);
        // 总价
        $totalFee = $goods['fee'] * $goodsNum;
        // 下单
        $order = RecPayOrder::create([
            'id' => date('YmdHis') . mt_rand(1000, 9999),
            'user_id' => $this->uid,
            'total_fee' => $totalFee,
            'coin' => isset($goods['coin']) ? $goods['coin'] : 0,
            'stone' => isset($goods['stone']) ? $goods['stone'] : 0,
            'item_id' => isset($goods['item_id']) ? $goods['item_id'] : 0,
            'goods_info' => json_encode($goods, JSON_UNESCAPED_UNICODE), // 商品信息
            'friend_uid' => $user_id,
        ]);
        // 预支付
        $res = (new WxAPI())->unifiedorder([
            'body' => $goods['title'],
            'orderId' => $order['id'],
            'totalFee' => $totalFee,
            'notifyUrl' => 'https://' . $_SERVER['HTTP_HOST'] . '/api/v1/pay/notify',
            'tradeType' => 'JSAPI',
            'openid' => User::where('id', $this->uid)->value('openid'),
        ]);
        // 处理预支付数据
        (new WxPayService())->returnFront($res);
    }

    /**支付通知 */
    public function notify()
    {
        $wxPayService = new WxPayService();
        $data = $wxPayService->notifyHandle();
        $order = RecPayOrder::get($data['out_trade_no']);
        if ($data['total_fee'] == $order['total_fee'] * 100) {
            // 处理订单状态和业务
            Db::startTrans();
            try {
                // 更改订单状态
                $res = RecPayOrder::where(['id' => $data['out_trade_no']])->update(['pay_time' => $data['time_end']]);
                if (!$res) {
                    // 订单不存在或已完成支付
                    Db::rollback();
                    die();
                }
                // 支付成功 处理业务
                RecPayOrder::paySuccess($order);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
            }
        }
    }
}
