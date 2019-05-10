<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank as StarRankModel;
use app\base\service\Common;
use app\api\model\StarRankHistory;
use app\api\model\UserExt;
use app\api\model\Cfg;

class StarRank extends Base
{
    /**明星人气榜单 */
    public function getRankList()
    {
        $page = input('page', 1);
        $size = input('size', 10);
        $keywords = input('keywords', '');
        $sign = input('sign', 0);// 韩星榜
        $rankField = input('rankField', 'week_hot');
        $type = input('type', 0);

        $list = StarRankModel::getRankList($page, $size, $rankField, $keywords, $sign);

        if ($type == 1) {
            // 偷花倒计时
            $this->getUser();
            $res = UserExt::get(['user_id' => $this->uid]);
            $leftTime = json_decode($res['left_time']);
            foreach ($leftTime as &$value) {
                $time =  Cfg::getCfg('stealLimitTime') - (time() - $value);
                if ($time < 0) {
                    $time = 0;
                }
                $value = $time;
            }
            Common::res(['data' => [
                'list' => $list,
                'steal' => $leftTime,
                'steal_count' => Cfg::getCfg('stealCount')
            ]]);
        } else {
            Common::res(['data' => $list]);
        }
    }

    public function search()
    { }

    public function getRankHistory()
    {
        // $page = input('page', 1);
        // $size = input('size', 10);

        $res = StarRankHistory::all();
        Common::res(['data' => $res]);
    }
}
