<?php
namespace app\api\model;

use app\base\model\Base;

class User extends Base
{
    public function UserStar()
    {
        return $this->hasOne('UserStar', 'user_id', 'id');
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
        } else {
            self::where(['openid' => $data['openid']])->update(['session_key' => $data['session_key']]);
        }

        return $user['id'];
    }
}
