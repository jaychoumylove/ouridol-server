<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\UserProp;
use app\base\service\Common;

class Prop extends Base
{

    public function use()
    {
        $id = input('id');
        if (!$id) Common::res(['code' => 100]);

        $res = UserProp::use($id);
        Common::res(['data' => $res]);
    }

    /**
     * 灵丹兑换道具入口
     * @throws \think\exception\DbException
     */
    public function exchange ()
    {
        $num = input('num');
        $propId  = input('id');

        if ((int) $num < 1) Common::res(['code' => 100, 'msg' => '请输入正确的数量']);
        if ((int) $propId < 1) Common::res(['code' => 100, 'msg' => '请选择道具']);

        $this->getUser();
        $uid = $this->uid;

        UserProp::exchange($uid, $propId, $num);

        Common::res(['msg' => '兑换成功']);
    }
}
