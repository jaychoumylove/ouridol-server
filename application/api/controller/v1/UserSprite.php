<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\UserSprite as UserSpriteModel;
use app\base\service\Common;

class UserSprite extends Base
{
    public function info()
    {
        $user_id = input('user_id');
        $res = UserSpriteModel::getInfo($user_id);
        Common::res(['data' => $res]);
    }

    public function settle()
    {
        $user_id = input('user_id');
        $this->getUser();

        $earn = UserSpriteModel::settle($user_id);

        if ($user_id != $this->uid) {
            // 为别人收能量
            $myEarn = UserSpriteModel::getTip($earn, $this->uid);
            Common::res(['data' => $myEarn]);
        } else {
            Common::res(['data' => $earn]);
        }
    }

    public function upgrade()
    {
        $this->getUser();
        $type = input('type', 0);

        UserSpriteModel::upgrade($this->uid, $type);
        Common::res([]);
    }

    public function skill()
    {
        $type = input('type', 1);
        $skillList = UserSpriteModel::getSkill($type);
        Common::res(['data' => $skillList]);
    }
}
