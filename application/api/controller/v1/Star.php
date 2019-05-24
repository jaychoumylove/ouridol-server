<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\service\Star as StarService;
use app\api\model\Star as StarModel;
use app\base\service\Common;
use app\api\model\RecStarChart;
use GatewayClient\Gateway;
use app\api\model\User as UserModel;
use think\Db;
use app\api\model\UserStar;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\Rec;
use app\api\model\Cfg;

class Star extends Base
{
    public function getInfo()
    {
        $starid =  input('starid');
        if (!$starid) Common::res(['code' => 100]);

        $star = StarModel::with('StarRank')->where(['id' => $starid])->find();

        $starService = new StarService();
        $star['star_rank']['week_hot_rank'] = $starService->getRank($star['star_rank']['week_hot'], 'week_hot');
        // $star['star_rank']['month_hot_rank'] = $starService->getRank($star['star_rank']['month_hot'], 'month_hot');

        Common::res(['data' => $star]);
    }

    public function getChart()
    {
        $starid = input('starid');
        if (!$starid) Common::res(['code' => 100]);

        $res = RecStarChart::getLeastChart($starid);
        Common::res(['data' => $res]);
    }

    /**加入聊天室 */
    public function joinChart()
    {
        $star_id = input('star_id');
        $client_id = input('client_id');
        if (!$client_id || !$star_id) Common::res(['code' => 100]);

        Gateway::joinGroup($client_id, 'star_' . $star_id);
        Common::res([]);
    }
    public function leaveChart()
    {
        $star_id = input('star_id');
        $client_id = input('client_id');
        if (!$client_id || !$star_id) Common::res(['code' => 100]);

        Gateway::leaveGroup($client_id, 'star_' . $star_id);
        Common::res([]);
    }

    public function sendMsg()
    {
        $starid = input('starid');
        $content = input('content');
        if (!$starid || !$content) Common::res(['code' => 100]);

        $this->getUser();

        // 保存聊天记录
        $res = RecStarChart::create([
            'user_id' => $this->uid,
            'star_id' => $starid,
            'content' => $content,
            'create_time' => time(),
        ]);
        $res['user'] = UserModel::where(['id' => $this->uid])->field('nickname,avatarurl')->find();
        $res['user']['user_star'] = UserStar::get(['user_id' => $this->uid, 'star_id' => $starid]);

        // 推送socket消息
        Gateway::sendToGroup('star_' . $starid, json_encode([
            'type' => 'chartMsg',
            'data' => $res
        ], JSON_UNESCAPED_UNICODE));
        if ($res) {
            Common::res([]);
        } else {
            Common::res(['code' => 500]);
        }
    }

    /**贡献人气 */
    public function sendHot()
    {
        $starid = input('starid');
        $hot = input('hot');
        if (!$starid || !$hot) Common::res(['code' => 100]);
        $this->getUser();

        (new StarService())->sendHot($starid, $hot, $this->uid);
        Common::res([]);
    }

    /**加入圈子 */
    public function follow()
    {
        $starid = input('starid');
        if (!$starid) Common::res(['code' => 100]);
        $this->getUser();

        $uid = UserStar::joinNew($starid, $this->uid);
        UserRelation::join($starid, $uid);

        Common::res([]);
    }

    /**偷花 */
    public function steal()
    {
        $starid = input('starid');
        $index = input('index');
        if (!$starid) Common::res(['code' => 100]);
        $this->getUser();

        (new StarService())->steal($starid, $this->uid);
        UserExt::setTime($this->uid, $index);
        Common::res(['data' => ['count' => Cfg::getCfg('stealCount')]]);
    }

    /**明星动态 */
    public function dynamic()
    {
        $starid = input('starid');
        if (!$starid) Common::res(['code' => 100]);
        $res = Rec::with(['User ' => ['UserStar ' => ['Star']]])->where('target_star_id', $starid)->limit(10)->order('id desc')->select();

        Common::res(['data' => $res]);
    }
}
