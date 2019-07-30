<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\UserSprite as UserSpriteModel;
use app\base\service\Common;
use app\api\service\User;

class UserSprite extends Base
{
    public function info()
    {
        $user_id = input('user_id');
        if (!$user_id) Common::res(['code' => 100]);
        $this->getUser();
        $res = UserSpriteModel::getInfo($user_id, $this->uid);
        Common::res(['data' => $res]);
    }

    public function settle()
    {
        $user_id = input('user_id');
        if (!$user_id) Common::res(['code' => 100]);
        $this->getUser();

        $earn = UserSpriteModel::settle($user_id, $this->uid);

        if ($user_id != $this->uid) {
            // 为别人收能量
            $myEarn = UserSpriteModel::getTip($earn, $this->uid);
            Common::res(['data' => $myEarn]);
        } else {
            Common::res(['data' => $earn]);
        }
    }

    public function shortEarn()
    {
        $this->getUser();

        $info = UserSpriteModel::getInfo($this->uid, $this->uid);

        if ($info['isUseCard']) {
            (new User())->change($this->uid, [
                'coin' => $info['earnPer']
            ]);
        }

        Common::res([
            'data' => [
                'isUseCard' => $info['isUseCard'],
                'earn' => $info['earnPer']
            ]
        ]);
    }

    public function upgrade()
    {
        $this->getUser();
        $type = input('type', 0);

        UserSpriteModel::upgrade($this->uid, $type);
        Common::res();
    }

    public function skill()
    {
        $type = input('type', 1);
        $skillList = UserSpriteModel::getSkill($type);
        Common::res(['data' => $skillList]);
    }
}
