<?php
/**
 * Created by PhpStorm.
 * User: qucaixian
 * Date: 2020/6/11
 * Time: 14:49
 */

namespace app\api\controller\v1;


use app\api\model\UserFather as UserFatherModel;
use app\base\controller\Base;
use app\base\service\Common;

class UserFather extends Base
{
    /**
     * 反出师门
     * @throws \think\exception\DbException
     */
    public function resetFather ()
    {
        $this->getUser();

        $relation = ['son' => $this->uid];

        $res = UserFatherModel::get($relation);
        if (empty($res)) Common::res(['code' => 1, 'msg' => '师徒关系已解除']);

        UserFatherModel::breakRelationship($relation);

        Common::res(['msg' => '师徒关系已解除']);
    }

    /**
     * 逐出师门
     * @throws \think\exception\DbException
     */
    public function resetSon ()
    {
        $id = input('id', 0);
        if ((int)$id < 1) Common::res(['msg' => '请选择解除徒弟', 'code' => 1]);

        $this->getUser();

        $relation = [
            'son'    => $id,
            'father' => $this->uid
        ];

        $res = UserFatherModel::get($relation);
        if (empty($res)) Common::res(['code' => 1, 'msg' => '师徒关系已解除']);

        UserFatherModel::breakRelationship($relation);

        Common::res(['msg' => '师徒关系已解除']);
    }
}