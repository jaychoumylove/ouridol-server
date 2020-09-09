<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\alipay\request\AlipayTradeWapPayRequest;
use app\base\service\AliPayApi;
use app\base\service\Common;
use app\api\model\PayGoods;
use app\api\model\RecPayOrder;
use app\base\service\WxAPI;
use app\api\model\User;
use app\base\service\WxPay as WxPayService;
use think\Db;
use app\api\model\UserStar;
use app\api\model\Star;
use think\Log;

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
        $goodsId = input('goods_id'); // 商品id
        $goodsNum = input('goods_num', 1); // 商品数量
        $user_id = input('user_id', 0); // 代充值uid
        $type = input('type', 0); // 购买类型
        $pay_type = input('pay_type', 'WECHAT_PAY'); // 购买类型
        if ($user_id == $this->uid) $user_id = 0;
        if (!$goodsId) Common::res(['code' => 100]);
        if($user_id!=0){
            $uid_platform = User::where(['id' => $user_id])->value('platform');
            $self_platform = User::where(['id' => $this->uid])->value('platform');
            if($uid_platform != $self_platform){
                if($uid_platform == 'MP-WEIXIN' && $self_platform == 'MP-QQ'){
                    Common::res(['code' => 1, 'msg' => '不能给微信用户充值']);
                }else if($self_platform == 'MP-WEIXIN' && $uid_platform == 'MP-QQ'){
                    Common::res(['code' => 1, 'msg' => '不能给QQ用户充值']);
                }
            }
        }


        // 商品
        $goods = PayGoods::getInfo($this->uid, $goodsId, $goodsNum, $type);
        // 总价
        $totalFee = $goods['fee'] * $goodsNum;
        if ($pay_type == 'WECHAT_PAY') {
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
                'body' => $goods['title'], // 支付标题
                'orderId' => $order['id'], // 订单ID
                'totalFee' => $totalFee, // 支付金额
                'notifyUrl' => 'https://' . $_SERVER['HTTP_HOST'] . '/api/v1/pay/notify', // 支付成功通知url
                'tradeType' => 'JSAPI', // 支付类型
                'openid' => User::where('id', $this->uid)->value('openid'), // 用户openid
            ]);
            // 处理预支付数据
            (new WxPayService())->returnFront($res);
        }

        if ($pay_type == 'ALI_PAI') {
            $totalFee = number_format($totalFee, 2);

            $aop = AliPayApi::getInstance();

            $request = new AlipayTradeWapPayRequest ();

            $data = [
                'body' => "",
                'subject' => "充值",
                'out_trade_no' => $order['id'],
                'timeout_express' => "10m", // 支付截止时间
                'total_amount' => $totalFee, //
                'product_code' => "QUICK_WAP_WAY",
            ];
            $request->setBizContent(json_encode($data));
            $notifyUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/api/v1/pay/alipaynotify';
            $request->setNotifyUrl($notifyUrl);
            $returnUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/#/pages/recharge/recharge';
            $request->setReturnUrl($returnUrl);
            $result = $aop->pageExecute($request);
//            echo $result;
            Common::res(['data' => $result]);
        }
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
                $isDone = RecPayOrder::where(['id' => $data['out_trade_no']])->update(['pay_time' => $data['time_end']]);
                if ($isDone) {
                    // 支付成功 处理业务
                    RecPayOrder::paySuccess($order);
                    Db::commit();

                    $wxPayService->returnSuccess();
                }
            } catch (\Exception $e) {
                Db::rollback();
            }
        }
    }

    public function alipayNotify()
    {
        $data = request()->post();

        if ($data['trade_status'] != 'TRADE_SUCCESS') {
            Log::record("交易出错", 'error');
            Log::error(json_encode($data));
            die();
        }

        $aop = AliPayApi::getInstance();
        // 验签
        $res = $aop->rsaCheckV1($data, $aop->alipayrsaPublicKey, $data['sign_type']);
        if (empty($res)) {
            Log::record("验签错误", 'error');
            Log::error(json_encode($data));
            die();
        }

        $order = RecPayOrder::get($data['out_trade_no']);
        if (empty($order)) {
            Log::record("订单号找不到", 'error');
            Log::error(json_encode($data));
            die();
        }
        if ($order['pay_time'] && $order['pay_time'] == $data['gmt_payment']) {
            Log::record("订单已处理", 'error');
            Log::error(json_encode($data));
            echo "success";
            die();
        }
        // 处理订单状态和业务
        Db::startTrans();
        try {
            // 更改订单状态
            $isDone = RecPayOrder::where(['id' => $data['out_trade_no']])->update(['pay_time' => $data['gmt_payment']]);
            if ($isDone) {
                // 支付成功 处理业务
                RecPayOrder::paySuccess($order);
                Db::commit();
            }
        } catch (\Exception $e) {
            Db::rollback();
            Log::record($e->getMessage(), 'error');
            die();
        }

        echo "success";
        die();
    }
}
