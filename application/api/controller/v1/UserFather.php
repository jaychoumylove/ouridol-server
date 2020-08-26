<?php
/**
 * Created by PhpStorm.
 * User: qucaixian
 * Date: 2020/6/11
 * Time: 14:49
 */

namespace app\api\controller\v1;


use app\api\model\CfgUserLevel;
use app\api\model\UserExt;
use app\api\model\UserFather as UserFatherModel;
use app\api\model\UserFatherApply;
use app\api\model\UserStar;
use app\api\model\Star;
use app\base\controller\Base;
use app\base\service\Common;

class UserFather extends Base
{
    /**
     * 反出师门
     * @throws \think\exception\DbException
     */
    public function resetFather()
    {
        $this->getUser();

        $relation = ['son' => $this->uid];

        $res = UserFatherModel::get($relation);
        if (empty($res)) Common::res(['code' => 1, 'msg' => '师徒关系已解除']);

        $isDone = UserStar::where('user_id', $this->uid)->update([
            'is_son' => 0
        ]);
        if ($isDone) {
            UserFatherModel::breakRelationship($relation);
        } else {
            Common::res(['code' => 1, 'msg' => '师徒解除失败']);
        }


        Common::res(['msg' => '师徒关系已解除']);
    }

    /**
     * 逐出师门
     * @throws \think\exception\DbException
     */
    public function resetSon()
    {
        $id = input('id', 0);
        if ((int)$id < 1) Common::res(['msg' => '请选择解除徒弟', 'code' => 1]);

        $this->getUser();

        $relation = [
            'son' => $id,
            'father' => $this->uid
        ];

        $res = UserFatherModel::get($relation);
        if (empty($res)) Common::res(['code' => 1, 'msg' => '师徒关系已解除']);

        $isDone = UserStar::where('user_id', $id)->update([
            'is_son' => 0
        ]);
        if ($isDone) {
            UserFatherModel::breakRelationship($relation);
        } else {
            Common::res(['code' => 1, 'msg' => '师徒解除失败']);
        }

        Common::res(['msg' => '师徒关系已解除']);
    }

    /**
     * 拜师
     * @throws \think\exception\DbException
     */
    public function fromFather()
    {
        $user_id = input('user_id', 0);
        if ((int)$user_id < 1) Common::res(['msg' => '参数错误', 'code' => 1]);
        $this->getUser();

        $res = UserFatherApply::apply($user_id, $this->uid, $this->uid);
        if ($res) {
            Common::res(['msg' => $res, 'code' => 1]);
        } else {
            Common::res(['msg' => '收徒申请成功']);
        }
    }

    /**
     * 收徒
     * @throws \think\exception\DbException
     */
    public function acceptSon()
    {
        $user_id = input('user_id', 0);
        if ((int)$user_id < 1) Common::res(['msg' => '参数错误', 'code' => 1]);
        $this->getUser();

        $res = UserFatherApply::apply($this->uid, $user_id, $this->uid);
        if ($res) {
            Common::res(['msg' => $res, 'code' => 1]);
        } else {
            Common::res(['msg' => '收徒申请成功']);
        }
    }

    /**
     * 师傅排行及未拜师用户排行
     * @throws \think\exception\DbException
     */
    public function fatherRank()
    {
        $field = input('field', 'fanther_rank');
        $page = input('page', 1);
        $size = input('size', 10);

        $this->getUser();

        if ($field == 'fanther_rank') {
            $list = UserExt::with('User')->where('father_get_count', '>', 0)
                ->field('id,user_id,father_get_count,father_get_time')
                ->order('father_get_count desc,father_get_time asc')
                ->page($page, $size)->select();
            foreach ($list as &$value) {
                $userStar = UserStar::where('user_id', $value['user_id'])->field('star_id,total_count')->find();
                $value['starname'] = Star::where('id', '=', $userStar['star_id'])->value('name');
                $value['level'] = CfgUserLevel::where('total', '<=', $userStar['total_count'])->max('level');
                $value['is_apply'] = UserFatherApply::where(['father' => $value['user_id'], 'son' => $this->uid])->find();
            }
        } else {
            $star_id = UserStar::where('user_id', $this->uid)->value('star_id');
            $list = UserStar::with('User')->where('star_id', $star_id)->field('id,user_id,star_id,is_son,thisday_count')
                ->where('is_son', 0)->where('thisday_count','>', 0)->order('thisday_count desc,total_count desc')
                ->page($page, $size)->select();
            foreach ($list as &$value) {
                $total_count = UserStar::where('user_id', $value['user_id'])->value('total_count');
                $value['level'] = CfgUserLevel::where('total', '<=', $total_count)->max('level');
                $value['is_apply'] = UserFatherApply::where(['father' => $this->uid, 'son' => $value['user_id']])->find();
            }
        }

        Common::res(['data' => $list]);
    }

    /**
     * 师徒申请列表
     * @throws \think\exception\DbException
     */
    public function applyList()
    {
        $page = input('page', 1);
        $size = input('size', 15);
        $type = input('type', 1);
        if ((int)$type < 1) Common::res(['msg' => '参数错误', 'code' => 1]);
        $this->getUser();

        $list = UserFatherApply::getList($this->uid, $type, $page, $size);

        Common::res(['data' => $list]);
    }

    /**
     * 师徒申请处理
     * @throws \think\exception\DbException
     */
    public function applyDeal()
    {
        $this->getUser();

        $id = input('id', 0);
        $status = input('status', 0);

        $info = UserFatherApply::get($id);
        if (!$info) Common::res(['code' => 1, 'msg' => '数据错误']);

        UserFatherApply::applydeal($this->uid, $id, $status);

        Common::res([]);
    }

}