<?php

namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\CfgItem;
use app\api\model\Open as OpenModel;
use app\api\model\OpenRank;
use app\api\model\OpenTop;
use app\api\model\Star;
use app\api\model\UserItem;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\base\service\Common;

class Open extends Base
{

    public function upload()
    {
        $imgUrl = $this->req('img_url', 'require');
        $this->getUser();
        $starId = UserStar::getStarId($this->uid);

        OpenModel::create(['user_id' => $this->uid, 'star_id' => $starId, 'img_url' => $imgUrl]);

        Common::res();
    }

    public function select()
    {
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $this->getUser();

        if ($page == 1) {
            // 昨日榜首
            $res['yestoday'] = OpenTop::get(['date' => date("Ymd", strtotime("-1 day"))]);
            $res['yestoday']['tomorrow'] = date("m", strtotime("+1 day")) . '月' . date("d", strtotime("+1 day")) . '日';
        }
        // 列表
        $res['list'] = OpenModel::getRankList($page, $size, 0);

        // 礼物
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
            if (!$value['self']) $value['self'] = 0;
        }
        Common::res(['data' => $res]);
    }

    public function settle()
    {
        // 获取榜首数据
        $topOpen = OpenModel::where('1=1')->order('hot desc,id desc')->find();
        $starname = Star::where('id', $topOpen['star_id'])->value('name');
        $userRank = OpenRank::with('User')->where('open_id', $topOpen['id'])->limit(3)->order('count desc,id asc')->select();

        $res = OpenTop::create([
            'open_id' => $topOpen['id'],
            'open_img' => $topOpen['img_url'],
            'starname' => $starname,
            'user_rank' => json_encode($userRank),
            'date' => date("Ymd", strtotime("-1 day"))
        ]);

        // 清空
        OpenModel::where('1=1')->update(['hot' => 0]);
        OpenRank::where('1=1')->update(['count' => 0]);

        if ($res) Common::res();
    }

    public function today()
    {
        $res['img'] = Cfg::getCfg('open_img');

        if (!$res['img']) {
            // 助力开屏
            $topInfo = OpenTop::where('date', date("Ymd", strtotime("-1 day")))->find();
            $res['img'] = $topInfo['open_img'];
            $res['starname'] = $topInfo['starname'];
            $res['user'] = $topInfo['user_rank'][0];
        }

        Common::res(['data' => $res]);
    }
}
