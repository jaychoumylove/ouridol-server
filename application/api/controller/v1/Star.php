<?php

namespace app\api\controller\v1;

use app\api\model\StarRank as StarRankModel;
use app\api\model\UserProp;
use app\base\controller\Base;
use app\api\service\Star as StarService;
use app\api\model\Star as StarModel;
use app\base\service\Common;
use app\api\model\RecStarChart;
use app\api\model\User as UserModel;
use think\Db;
use app\api\model\UserStar;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\Rec;
use app\api\model\Cfg;
use app\base\service\WxAPI;
use app\api\model\UserSprite;
use GatewayWorker\Lib\Gateway;

class Star extends Base
{

    public function getInfo()
    {
        $starid = $this->req('starid', 'integer');

        $star = StarModel::with('StarRank')->where([
            'id' => $starid
        ])->find();

        $starService = new StarService();
        $star['star_rank']['week_hot_rank'] = $starService->getRank($star['star_rank']['week_hot'], 'week_hot');
        // $star['star_rank']['month_hot_rank'] = $starService->getRank($star['star_rank']['month_hot'], 'month_hot');

        Common::res([
            'data' => $star
        ]);
    }

    public function getChart()
    {
        $starid = $this->req('starid', 'integer');

        $res = RecStarChart::getLeastChart($starid);
        Common::res([
            'data' => $res
        ]);
    }

    /**
     * 加入聊天室
     */
    public function joinChart()
    {
        $star_id = $this->req('star_id', 'integer');
        $client_id = input('client_id');
        if (!$client_id || !$star_id)
            Common::res([
                'code' => 100
            ]);

        Gateway::joinGroup($client_id, 'star_' . $star_id);
        Common::res([]);
    }

    public function leaveChart()
    {
        $star_id = $this->req('star_id', 'integer');
        $client_id = input('client_id');
        if (!$client_id || !$star_id)
            Common::res([
                'code' => 100
            ]);

        Gateway::leaveGroup($client_id, 'star_' . $star_id);
        Common::res([]);
    }

    public function sendMsg()
    {
        $starid = $this->req('starid', 'integer');
        $content = $this->req('content', 'require');
        $client_id = input('client_id', NULL);
        $this->getUser();

        RecStarChart::sendMsg($this->uid, $starid, $content, $client_id);
        Common::res();
    }

    /**
     * 贡献人气
     */
    public function sendHot()
    {
        $rer_user_id = input('referrer', 0);
        $starid = $this->req('starid', 'integer');
        $openId = $this->req('open_id', 'number', 0); // 开屏图id
        $hot = input('hot'); // type=1 为礼物id
        $type = input('type', 0);
        if (!$starid || !$hot)
            Common::res([
                'code' => 100
            ]);
        $this->getUser();

        $res = (new StarService())->sendHot($starid, $hot, $this->uid, $type, $openId ,$rer_user_id);
        // 我的总贡献
        $res['totalCount'] = UserStar::where('user_id', $this->uid)->value('total_count');
        // 距离上一名
        $res['disLeastCount'] = StarModel::disLeastCount($starid);
        Common::res([
            'data' => $res
        ]);
    }

    /**
     * 加入圈子
     */
    public function follow()
    {
        $starid = $this->req('starid', 'integer');
        $platform = $this->req('platform', 'require', 'MP-WEIXIN'); // 平台
        if (!$starid)
            Common::res([
                'code' => 100
            ]);
        $this->getUser();

        $uid = UserStar::joinNew($starid, $this->uid, $platform);
        UserRelation::join($starid, $uid);

        Common::res([]);
    }

    public function automaticSteal()
    {
        $this->getUser();
        $type = $this->req('type', 'integer');
        if ($type < 0 || $type > 1) Common::res(['code' => 100]);
        $spriteLevel = UserSprite::where(['user_id' => $this->uid])->value('sprite_level');
        if ($type!=0 && $spriteLevel < 10) Common::res(['code' => 1, 'msg' => '精灵lv.10解锁自动偷取']);

        Db::startTrans();
        try {

            UserExt::where(['user_id' => $this->uid])->update(['is_automatic_steal' => $type]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res([]);
    }

    /**
     * 偷花
     */
    public function steal()
    {
        $starid = input('starid', '');
        $index = input('index');
        if ($starid && !is_numeric($starid)) Common::res(['code' => 100]);
        $this->getUser();

        $staridList = $this->staridList();

        $spriteLevel = UserSprite::where(['user_id' => $this->uid])->value('sprite_level');

        // 是否使用偷取多倍卡
        $useCard = UserProp::getMultipleStealCardVar($this->uid);

        $stealLimitTime = $useCard ? $useCard['cooling_time'] : Cfg::getCfg('stealLimitTime');
        $stealMultiple = $useCard ? $useCard['multiple'] : Cfg::getCfg('stealCount');

        $stealCount = $stealMultiple * $spriteLevel;

        if ($starid) {
            $index = array_search($starid, $staridList);
            $this->checkTime($stealLimitTime, $index);
            if (!in_array($starid, $staridList)) Common::res(['code' => 1, 'msg' => '不能偷取该爱豆']);
            (new StarService())->steal($starid, $this->uid, $stealCount, $index);
        } else {
            if ($spriteLevel < 5) Common::res(['code' => 1, 'msg' => '精灵达到5级解锁一键偷取']);
            $this->checkTime($stealLimitTime, $index);
            (new StarService())->stealAll($staridList, $this->uid, $stealCount);

            $stealCount = $stealCount * 5;
        }


        Common::res([
            'data' => [
                'count' => $stealCount,
                'steal' => $stealLimitTime,
                'staridStealList' => $staridList,
            ]
        ]);
    }

    public function staridList()
    {

        $my_star_id = UserStar::where('user_id', $this->uid)->value('star_id');
        $list = StarRankModel::getRankList(1, 11, 'week_hot', '', 0);
        $staridList = array_map(function ($element) {
            $newarray = [];
            if (array_key_exists('star_id', $element)) {
                $newarray = $element ['star_id'];
            }
            return $newarray;
        }, $list);

        foreach ($staridList as $key => $starid){

            if($my_star_id==$starid){
                unset($staridList[$key]);
                continue;
            }
            //守护爱豆不能偷
            if(Ext::is_start('is_guardian_active')){
                $guardian_active_info = ActivityGuardian::is_guardian($starid);
                if($guardian_active_info) {
                    unset($staridList[$key]);
                    continue;
                }
            }
        }

        $staridList = array_slice($staridList,0,5);

        return $staridList;
    }

    public function checkTime($stealLimitTime, $index)
    {

        $left_time = UserExt::where(['user_id' => $this->uid])->value('left_time');
        $leftTime = json_decode($left_time, true);
        if ($index >= 0) {
            if (time() - $leftTime[$index] < $stealLimitTime) Common::res(['code' => 1, 'msg' => '冷却中，请稍等']);
        } else {
            foreach ($leftTime as $value) {
                if (time() - $value < $stealLimitTime) Common::res(['code' => 1, 'msg' => '冷却中，请稍等']);
            }
        }
    }

    /**
     * 明星动态
     */
    public function dynamic()
    {
        $starid = $this->req('starid', 'integer');
        if (!$starid)
            Common::res([
                'code' => 100
            ]);
        $res = Rec::with([
            'User ' => [
                'UserStar ' => [
                    'Star'
                ]
            ]
        ])->where('target_star_id', $starid)
            ->limit(10)
            ->order('id desc')
            ->select();

        Common::res([
            'data' => $res
        ]);
    }
}
