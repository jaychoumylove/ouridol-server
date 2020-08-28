<?php

namespace app\api\controller\v1;

use app\api\model\UserProp;
use app\api\model\UserStar;
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

        $list = StarRankModel::getRankList($page, $size, $rankField, $keywords, $sign);
        Common::res(['data' => $list]);
    }

    /**明星人气榜单 */
    public function getStealRankList()
    {
        $this->getUser();

        $list = StarRankModel::getRankList(1, 11, 'week_hot', '', 0);
        // 偷花倒计时
        $res = UserExt::get(['user_id' => $this->uid]);
        // 获取使用偷取多倍卡信息
        $useCard = UserProp::getMultipleStealCardVar($this->uid);

        $stealLimitTime = $useCard ? $useCard['cooling_time'] : Cfg::getCfg('stealLimitTime');
        $leftTime = json_decode($res['left_time']);
        foreach ($leftTime as &$t) {
            $time =  $stealLimitTime - (time() - $t);
            if ($time < 0) {
                $time = 0;
            }
            $t = $time;
        }
        $spriteLevel = UserSprite::where(['user_id' => $this->uid])->value('sprite_level');
        $star_id = UserStar::where(['user_id' => $this->uid])->value('star_id');
        $stealMultiple = $useCard ? $useCard['multiple'] : Cfg::getCfg('stealCount');
        $num = 0;//用作截取数组
        $index = 0;//判断什么时候截取数组
        foreach ($list as $key=>$value){
            if($star_id==$value['star_id']){
                unset($list[$key]);
                continue;
            }
            //是否开启守护
            $list[$key]['guardian_active_info'] = '';
            if(Ext::is_start('is_guardian_active')){
                $list[$key]['guardian_active_info'] = ActivityGuardian::is_guardian($value['star_id']);
            }
            if($index<5){
                if(!$list[$key]['guardian_active_info']){
                    $index++;
                }
            }else{
                $num =$key-1;
                break;
            }
        }

        $list = array_slice($list,0,$num);


        Common::res(['data' => [
            'list' => $list,
            'steal' => $leftTime,
            'steal_count' => bcmul($stealMultiple, $spriteLevel),
            'sprite_level' => $spriteLevel,
            'steal_times' => $res['steal_times'],
            'steal_times_max' => Cfg::getCfg('steal_limit'),
            'steal_num' => $res['steal_count'],
            'steal_num_max' => Star::stealCountLimit($this->uid),
            'is_automatic_steal' => UserExt::where('user_id', $this->uid)->value('is_automatic_steal')
        ]]);
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
