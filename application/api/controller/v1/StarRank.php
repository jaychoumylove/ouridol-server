<?php

namespace app\api\controller\v1;

use app\api\model\UserProp;
use app\base\controller\Base;
use app\api\model\StarRank as StarRankModel;
use app\base\service\Common;
use app\api\model\StarRankHistory;
use app\api\model\UserExt;
use app\api\model\Cfg;
use app\api\model\UserSprite;
use app\api\service\Star;

class StarRank extends Base
{
    /**明星人气榜单 */
    public function getRankList()
    {
        $page = input('page', 1);
        $size = input('size', 10);
        $keywords = input('keywords', '');
        $sign = input('sign', 0); // 韩星榜
        $rankField = input('rankField', 'week_hot');
        $type = input('type', 0);

        $list = StarRankModel::getRankList($page, $size, $rankField, $keywords, $sign);
        if ($type == 1) {
            // 偷花倒计时
            $this->getUser();
            $res = UserExt::get(['user_id' => $this->uid]);
            // 获取使用偷取多倍卡信息
            $useCard = UserProp::getMultipleStealCardVar($this->uid);

            $stealLimitTime = $useCard ? $useCard['cooling_time'] : Cfg::getCfg('stealLimitTime');
            $leftTime = json_decode($res['left_time']);
            foreach ($leftTime as &$value) {
                $time =  $stealLimitTime - (time() - $value);
                if ($time < 0) {
                    $time = 0;
                }
                $value = $time;
            }
            $spriteLevel = UserSprite::where(['user_id' => $this->uid])->value('sprite_level');

            $stealMultiple = $useCard ? $useCard['multiple'] : Cfg::getCfg('stealCount');

            Common::res(['data' => [
                'list' => $list,
                'steal' => $leftTime,
                'steal_count' => bcmul($stealMultiple, $spriteLevel),
                'sprite_level' => $spriteLevel,
                'steal_times' => $res['steal_times'],
                'steal_times_max' => Cfg::getCfg('steal_limit'),
                'steal_num' => $res['steal_count'],
                'steal_num_max' => Star::stealCountLimit($this->uid)
            ]]);
        } else {
            Common::res(['data' => $list]);
        }
    }

    public function search()
    { }

    /**历史榜单 */
    public function getRankHistory()
    {
        // $page = input('page', 1);
        // $size = input('size', 10);

        $rankField = input('rankField', 'week_hot');

        $res = StarRankHistory::where(['field' => $rankField])->order('date desc')->select();

        foreach ($res as &$value) {
            $year = substr($value['date'], 0, 4);
            if ($rankField == 'week_hot') {
                $week = substr($value['date'], -2);
                $value['date'] = $year . '年' . $week . '周';
            } else if ($rankField == 'month_hot') {
                $month = substr($value['date'], -2);
                $value['date'] = $year . '年' . $month . '月';
            }
        }
        Common::res(['data' => $res]);
    }
}
