<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\ShareMass;
use app\base\service\Common;
use app\api\model\UserFather;
use app\api\model\Cfg;

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

    public function massJoin()
    {
        $rer_user_id = input('referrer');
        if (!$rer_user_id) Common::res(['code' => 100]);
        $this->getUser();

        ShareMass::join($rer_user_id, $this->uid);
        UserFather::join($rer_user_id, $this->uid);
        Common::res([]);
    }

    public function massSettle()
    {
        $this->getUser();

        $earn = ShareMass::settle($this->uid);
        Common::res(['data' => $earn]);
    }

    public function father()
    {
        $this->getUser();

        $res = UserFather::with('User')->where(['father' => $this->uid])->select();
        if ($res) {
            foreach ($res as $key => &$value) {
                $value['user_star'] = $value['user']['user_star'];
                $value['user_earn'] = ceil(Cfg::getCfg('father_earn_per') * $value['user_star']['thisday_count']);
                // 排序
                $sort[$key] = $value['user_star']['thisday_count'];
            }
            array_multisort($sort, SORT_DESC, $res);
        }


        Common::res(['data' => $res]);
    }
}
