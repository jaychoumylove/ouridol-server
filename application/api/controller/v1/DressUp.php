<?php

namespace app\api\controller\v1;

use app\api\model\CfgBadge;
use app\api\model\CfgHeadwear;
use app\api\model\CfgSpriteBg;
use app\api\model\UserHeadwear;
use app\api\model\UserSpriteBg;
use app\base\controller\Base;
use app\base\service\Common;

class DressUp extends Base
{
    public function select()
    {
        $this->getUser();
        $type = $this->req('type', 'integer', '0');

        if ($type == 0) {//精灵背景
            $res = UserSpriteBg::getAll($this->uid);
        } elseif ($type == 1) {//聊天头饰
            $res = CfgHeadwear::getAll($this->uid);
        } elseif ($type == 2) {//徽章
            Common::res(['code' => 1, 'msg' => '敬请期待']);
            $res = CfgBadge::getAll($this->uid);
        }

        Common::res(['data' => $res]);
    }

    public function useIt()
    {
        $this->getUser();

        $id = $this->req('id', 'integer', '0');
        $type = $this->req('type', 'integer', '0');

        if ($type == 0) {//精灵背景
            UserSpriteBg::useIt($this->uid, $id);
        } elseif ($type == 1) {//聊天头饰
            UserHeadwear::useIt($this->uid, $id);
        } elseif ($type == 2) {//徽章
            Common::res(['code' => 1, 'msg' => '敬请期待']);
            CfgBadge::badgeUse($this->uid, $id);
        }

        Common::res([]);
    }

    public function buy()
    {
        $this->getUser();

        $type = $this->req('type', 'integer', '0');
        $id = $this->req('id', 'integer', '0');

        if ($type == 0) {//精灵背景
            UserSpriteBg::buyIt($this->uid, $id);
        } elseif ($type == 1) {//聊天头饰
            UserHeadwear::buyIt($this->uid, $id);
        } elseif ($type == 2) {//徽章
            Common::res(['code' => 1, 'msg' => '敬请期待']);
        }

        Common::res([]);
    }


    public function cancel()
    {
        $this->getUser();

        $id = $this->req('id', 'integer', '0');
        $type = $this->req('type', 'integer', '0');

        if ($type == 0) {//精灵背景
            Common::res(['code' => 1, 'msg' => '敬请期待']);
        } elseif ($type == 1) {//聊天头饰
            UserHeadwear::cancel($this->uid);
        } elseif ($type == 2) {//徽章
            Common::res(['code' => 1, 'msg' => '敬请期待']);
            CfgBadge::badgeCancel($this->uid, $id);
        }


        Common::res();
    }

    //解锁
    public function unlock()
    {
        $this->getUser();
        $id = $this->req('id', 'integer', '0');
        $type = $this->req('type', 'integer', '0');

        if ($type == 0) {//精灵背景
            UserSpriteBg::unlockIt($this->uid, $id);
        } elseif ($type == 1) {//聊天头饰
            UserHeadwear::unlockIt($this->uid, $id);
        } elseif ($type == 2) {//徽章
            Common::res(['code' => 1, 'msg' => '敬请期待']);
        }

        Common::res([]);
    }

}
