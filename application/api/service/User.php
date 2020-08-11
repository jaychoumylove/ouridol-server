<?php

namespace app\api\service;

use app\base\service\WxAPI;
use app\base\service\Common;
use app\api\model\User as UserModel;
use app\api\model\UserCurrency;
use think\Db;
use app\api\model\UserRelation;
use app\api\model\Rec;
use app\api\model\Cfg;
use app\api\model\CfgSignin;
use app\api\model\UserExt;

class User
{
    /**
     * 获取用户openid等信息
     */
    public function wxGetAuth($code, $platform)
    {
        if ($platform == 'MP-WEIXIN') {
            // 微信小程序登录
            $wxApi = new WxAPI('miniapp');
            $res = $wxApi->code2session($code);
        } else if ($platform == 'MP-QQ') {
            // QQ小程序登录
            $wxApi = new WxAPI('qq');
            $res = $wxApi->code2session($code);
        } else if ($platform == 'H5') {
            // 微信授权网页登录
            $wxApi = new WxAPI('wx00cf0e6d01bb8b01');
            $res = $wxApi->getAuth($code);
            // code has been used
            if (isset($res['errcode']) && $res['errcode'] == 40163) Common::res(['msg' => '已登录']);
            // if (isset($res['unionid'])) {
            //     $res['openid'] = UserModel::where(['unionid' => $res['unionid']])->value('openid');
            //     if (!$res['openid']) Common::res(['code' => 202, 'msg' => '请先到同名小程序进行用户授权']);
            // } else {
            //     Log::record('未获取到用户信息，缺少unionid', 'error');
            //     Common::res(['code' => 202, 'msg' => '未获取到用户信息，缺少unionid']);
            // }
        }

        if (!isset($res['openid']) || !$res['openid']) {
            // 登录失败
            Common::res(['code' => 202, 'data' => $res]);
        } else {
            return $res;
        }
    }

    /**
     * 货币变动
     * @param int $uid
     * @param array $currency 货币增减额
     * @param array $rec 日志存入 ['type' => 1, 'target_user_id' => 2, 'target_star_id' => 3]
     */
    public function change($uid, $currency, $rec = null)
    {
        $userCurrency = UserCurrency::get(['uid' => $uid]);

        $update = [];
        foreach ($currency as $key => $value) {
            if ($value > 0) {
                // 增加
                $value = '+' . $value;
            } else if ($value < 0) {
                // 减少
                if ($userCurrency[$key] < $value / -1) {
                    // 货币不足
                    switch ($key) {
                        case 'coin':
                            Common::res(['msg' => '能量不足', 'data' => ['nomore' => true]]);
                            break;
                        case 'stone':
                            Common::res(['code' => 1, 'msg' => '灵丹不足']);
                            break;
                        case 'trumpet':
                            Common::res(['code' => 1, 'msg' => '喇叭不足']);
                            break;

                        default:
                            # code...
                            break;
                    }
                }
            } else {
                continue;
            }
            $update[$key] = Db::raw($key . $value);
        }
        if (!$update) return;
        UserCurrency::where(['uid' => $uid])->update($update);

        if ($rec) {
            // 记录日志
            $recSave = ['user_id' => $uid, 'type' => $rec['type']];
            $recSave['coin'] = isset($currency['coin']) ? $currency['coin'] : 0;
            $recSave['stone'] = isset($currency['stone']) ? $currency['stone'] : 0;
            $recSave['trumpet'] = isset($currency['trumpet']) ? $currency['trumpet'] : 0;
            $recSave['target_user_id'] = isset($rec['target_user_id']) ? $rec['target_user_id'] : null;
            $recSave['target_star_id'] = isset($rec['target_star_id']) ? $rec['target_star_id'] : null;
            $recSave['content'] = isset($rec['content']) ? $rec['content'] : null;

            Rec::addRec($recSave);
        }
    }

    public function getInvitAward($ral_user_id, $uid)
    {
        Db::startTrans();
        try {
            $res = UserRelation::where([
                'rer_user_id' => $uid,
                'ral_user_id' => $ral_user_id,
            ])->update([
                'status' => 2,
            ]);
            if (!$res) Common::res(['code' => 1]);

            $this->change($uid, Cfg::getCfg('invitAward'), [
                'type' => 17
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }

    /**
     * 连续签到
     */
    public function signin($uid)
    {
        // 判定签到
        $ext = UserExt::get(['user_id' => $uid]);

        if (date('Ymd', time()) == date('Ymd', $ext['signin_time'])) {
            // 今日已签到
            return ['signin_day' => $ext['signin_day']];
        }

        if (date('Ymd', $ext['signin_time']) == date("Ymd", strtotime("-1 day"))) {
            // 连续签到
            $ext['signin_day'] += 1;
        } else {
            // 第一天签到或中途断签
            $ext['signin_day'] = 1;
        }

        // 奖励数额
        $coin = CfgSignin::where('days', '<=', $ext['signin_day'])->order('days desc')->value('coin');

        Db::startTrans();
        try {

            UserExt::where(['user_id' => $uid])->update([
                'signin_day' => $ext['signin_day'],
                'signin_time' => time(),
            ]);

            (new User())->change($uid, [
                'coin' => $coin,
            ], [
                'type' => 10
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        return [
            'coin' => $coin,
            'signin_day' => $ext['signin_day']
        ];
    }
}
