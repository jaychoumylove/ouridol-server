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

class Payment extends Base
{
    public function goods()
    {
        Common::res(['data' => PayGoods::all()]);
    }

    /**下单 */
    public function order()
    {
        $this->getUser();
        $goodsId = input('goods_id');
        $goodsNum = input('goods_num', 1);
        if (!$goodsId) Common::res(['code' => 100]);
        // 商品
        $goods = PayGoods::get($goodsId);
        if (!$goods) Common::res(['code' => 303]);

        $totalFee = $goods['fee'] * $goodsNum;
        // 下单
        $order = RecPayOrder::create([
            'id' => date('YmdHis') . mt_rand(1000, 9999),
            'user_id' => $this->uid,
            'total_fee' => $totalFee,
            'coin' => $goods['coin'],
            'stone' => $goods['stone'],
        ]);
        // 预支付
        $res = (new WxAPI())->unifiedorder([
            'body' => $goods['title'],
            'orderId' => $order['id'],
            'totalFee' => $totalFee,
            'notifyUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/api/v1/pay/notify',
            'tradeType' => 'JSAPI',
            'openid' => User::get($this->uid)['openid'],
        ]);
        // 返回数据
        (new WxPayService())->returnFont($res);
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
                $res = RecPayOrder::where(['id' => $data['out_trade_no']])->update(['pay_time' => $data['time_end']]);
                if (!$res) die();

                (new UserService())->change($order['user_id'], [
                    'coin' => $order['coin'],
                    'stone' => $order['stone'],
                ]);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
            }
        }
    }
}
