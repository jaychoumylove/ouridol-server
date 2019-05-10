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

class User
{

    public function wxGetAuth($js_code)
    {
        $WxAPI = new WxAPI();
        $res = $WxAPI->code2session($js_code);

        if (!isset($res['openid'])) {
            Common::res(['code' => 202, 'data' => $res]);
        } else {
            return $res;
        }
    }

    /**
     * 货币变动
     * @params int $uid
     * @params array $currency 货币增减额
     * @params array $rec 日志存入
     */
    public function change($uid, $currency, $rec = null)
    {
        $userCurrency = UserCurrency::get(['uid' => $uid]);

        $update = [];
        foreach ($currency as $key => $value) {
            if ($value >= 0) {
                // 增加
                $value = '+' . $value;
            } else {
                // 减少
                if ($userCurrency[$key] < $value / -1) {
                    // 货币不足
                    switch ($key) {
                        case 'coin':
                            Common::res(['code' => 1, 'msg' => '能量不足']);
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
            }
            $update[$key] = Db::raw($key . $value);
        }
        UserCurrency::where(['uid' => $uid])->update($update);

        if ($rec) {
            // 记录日志
            $recSave = ['user_id' => $uid, 'type' => $rec['type']];
            $recSave['coin'] = isset($currency['coin']) ? $currency['coin'] : 0;
            $recSave['stone'] = isset($currency['stone']) ? $currency['stone'] : 0;
            $recSave['trumpet'] = isset($currency['trumpet']) ? $currency['trumpet'] : 0;
            $recSave['target_user_id'] = isset($rec['target_user_id']) ? $rec['target_user_id'] : null;
            $recSave['target_star_id'] = isset($rec['target_star_id']) ? $rec['target_star_id'] : null;

            Rec::create($recSave);
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
                'status' => 1,
            ]);
            if (!$res) Common::res(['code' => 1]);

            $this->change($uid, Cfg::getCfg('invitAward'));

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400]);
        }
    }
}
