<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserStar;

class UserRank extends Base
{
    public function getRank()
    {
        $starid = input('starid', null);
        $type = input('type', 0);

        switch ($type) {
            case 0:
                $field = 'thisweek_count';
                break;

            default:
                # code...
                break;
        }
        $page = input('page', 1);
        $size = input('size', 10);

        $res['list'] = UserStar::getRank($starid, $field, $page, $size);
        $this->getUser();
        $res['my'] = UserStar::getMyRankInfo($this->uid, $starid, $field);
        Common::res(['data' => $res]);
    }
}
