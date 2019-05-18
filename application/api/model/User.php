<?php
namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class User extends Base
{
    public function UserStar()
    {
        return $this->hasOne('UserStar', 'user_id', 'id', [], 'LEFT');
    }

    public function Sprite()
    {
        return $this->hasOne('UserSprite', 'user_id', 'id');
    }

    /**
     * 创建一个新用户
     */
    public static function saveUser($data)
    {
        Db::startTrans();
        try {
            $user = self::get(['openid' => $data['openid']]);
            if (!$user) {
                // 新用户
                // User
                $shortCode = strtoupper(substr(md5(md5($data['openid'])), 0, 6));
                $insert = [
                    'openid' => $data['openid'],
                    'unionid' => isset($data['unionid']) ? $data['unionid'] : null,
                    'session_key' => isset($data['session_key']) ? $data['session_key'] : null,
                    'ident_code' => $shortCode,
                    'platform' => isset($data['platform']) ? $data['platform'] : null,
                    'model' => isset($data['model']) ? $data['model'] : null,
                    'type' => isset($data['type']) ? $data['type'] : 0,
                    'nickname' => isset($data['nickname']) ? $data['nickname'] : null,
                    'avatarurl' => isset($data['avatarurl']) ? $data['avatarurl'] : null,
                ];

                $user = self::create($insert);
                // UserCurrency
                $currency = [
                    'uid' => $user['id'],
                ];
                if (isset($data['type']) && $data['type'] == 1) {
                    $currency['coin'] =  100000;
                    $currency['stone'] =  300;
                    $currency['trumpet'] =  100;
                }
                UserCurrency::create($currency);

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

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400]);
        }

        return $user['id'];
    }

    /**创建虚拟用户 */
    public static function createVirtualUser($data)
    {
        $vrNickname = OtherFakeUser::where('1=1')->orderRaw('rand()')->value('nickname');
        $vrAvatar = OtherFakeUser::where('1=1')->orderRaw('rand()')->value('avatar');

        return self::saveUser([
            'openid' => $data['openid'],
            'unionid' => $data['unionid'],
            'nickname' => $vrNickname,
            'avatarurl' => $vrAvatar,
            'type' => 1 // 虚拟用户type
        ]);
    }
}
