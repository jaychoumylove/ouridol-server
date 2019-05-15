<?php
namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class User extends Base
{
    public function UserStar()
    {
        return $this->hasOne('UserStar', 'user_id', 'id');
    }

    public function Sprite()
    {
        return $this->hasOne('UserSprite', 'user_id', 'id');
    }

    public static function saveUser($data)
    {
        $user = self::get(['openid' => $data['openid']]);
        if (!$user) {
            // 新用户
            // User
            $shortCode = strtoupper(substr(md5(md5($data['openid'])), 0, 6));
            $insert = [
                'openid' => $data['openid'],
                'session_key' => $data['session_key'],
                'ident_code' => $shortCode,
                'platform' => $data['platform'],
                'model' => $data['model'],
            ];
            if (isset($data['unionid']) && $data['unionid']) {
                $insert['unionid'] = $data['unionid'];
            }
            
            Db::startTrans();
            try {
                $user = self::create($insert);
                // UserCurrency
                UserCurrency::create([
                    'uid' => $user['id'],
                ]);

                // UserExt
                UserExt::create([
                    'user_id' => $user['id'],
                    'left_time' => json_encode([0, 0, 0, 0, 0]),
                ]);

                // UserSprite
                UserSprite::create([
                    'user_id' => $user['id'],
                    'settle_time' => time(),
                ]);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400]);
            }
        } else {
            self::where(['openid' => $data['openid']])->update(['session_key' => $data['session_key']]);
        }

        return $user['id'];
    }
}
