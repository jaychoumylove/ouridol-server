<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\ShareMass;
use app\base\service\Common;
use app\api\model\UserFather;
use app\api\model\Cfg;
use think\Db;
use app\api\model\UserRelation;

class Share extends Base
{

    public function mass()
    {
        $this->getUser();
        Common::res(['data' => ShareMass::getMass($this->uid)]);
    }

    public function massStart()
    {
        $this->getUser();
        ShareMass::start($this->uid);
        Common::res([]);
    }

    /**
     * 从分享入口进入每次都会调用该接口
     */
    public function massJoin()
    {
        $rer_user_id = input('referrer');
        if (!$rer_user_id) Common::res(['code' => 100]);
        $this->getUser();

        // 加入集结
        ShareMass::join($rer_user_id, $this->uid);
        // 师徒关系
        UserFather::join($rer_user_id, $this->uid);
        // 拉新关系
        UserRelation::saveNew($this->uid, $rer_user_id);
        Common::res([]);
    }

    public function massSettle()
    {
        $this->getUser();

        $earn = ShareMass::settle($this->uid);
        Common::res(['data' => $earn]);
    }

    /**师徒关系列表 */
    public function father()
    {
        $this->getUser();

        $res = UserFather::getFatherList($this->uid);

        Common::res(['data' => $res]);
    }

    /**获取徒弟的收益 */
    public function sonEarn()
    {
        $sonUid = input('user_id', 0);
        $this->getUser();
        $earn = UserFather::sonEarn($this->uid, $sonUid);
        Common::res(['data' => $earn]);
    }

    public function checkEarn()
    {
        $this->getUser();
        $cur_contribute = UserFather::where(['father' => $this->uid])->max('cur_contribute');
        $earn = floor($cur_contribute * Cfg::getCfg('father_earn_per'));
        Common::res(['data' => $earn]);
    }
}
