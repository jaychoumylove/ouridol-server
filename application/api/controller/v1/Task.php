<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Task as TaskModel;
use app\base\service\Common;
use app\api\service\Task as TaskService;
use app\api\model\User;
use app\api\service\Star;
use app\api\model\Cfg;
use app\api\model\CfgBadge;
use app\api\model\UserActive;
use app\api\model\UserStar;

class Task extends Base
{
    public function index()
    {
        $category = $this->req('category', 'integer');
        $this->getUser();

        if ($category == 2) {
            // 徽章
            $list = CfgBadge::getList($this->uid);
        } else {
            // 任务
            $list = (new TaskService())->checkTask($this->uid, $category);
        }

        Common::res(['data' => $list]);
    }

    public function settle()
    {
        $task_id = input('task_id');
        $this->getUser();

        $earn = (new TaskService())->settle($task_id, $this->uid);
        Common::res(['data' => $earn]);
    }

    /**提交微博链接 */
    public function weibo()
    {
        $this->getUser();
        $weiboUrl = input('weiboUrl');
        if (!$weiboUrl) Common::res(['code' => 100]);

        $type = input('type', 0);

        if ($type == 0) {
            $text = Cfg::getCfg('appname');
        } else if ($type == 1) {
            $text = Cfg::getCfg('weibo_zhuanfa')['pick_text'];
        }

        $taskService = new TaskService();
        $weiboUrl = $taskService->getWeiboUrl($weiboUrl);
        $taskService->saveWeibo($weiboUrl, $this->uid, $text, $type);
        Common::res([]);
    }


    public function sharetext()
    {
        $this->getUser();
        $user = User::with(['UserStar' => ['Star' => ['StarRank']]])->where(['id' => $this->uid])->find();
        $rank = (new Star())->getRank($user['user_star']['star']['star_rank']['week_hot'], 'week_hot');
        $type = input('type', 0);
        // $text = "我正在为#APPNAME#STARNAME打榜，STARNAME已经获得了STARSCORE票，实时排名第STARRANK，wx搜索小程序“APPNAME”，加入STARNAME的偶像圈，一起用爱解锁最强福利！";
        $map = [
            'weibo_share_text',
            'pyq_share_text',
            'weibo_share_text_1',
            'pyq_share_text_1',
            'weibo_share_text_2'
        ];

        if (array_key_exists ($type, $map)) {
            $text = Cfg::getCfg($map[$type]);
        }
        if (empty($text)) {
            Common::res (['code' => 1, 'msg' => "分享出错"]);
        }
        // $text = "#STARNAME[超话]#今天我已为爱豆打榜，STARNAME加油，我爱你，我会每天支持你，
        //     不离不弃。爱STARNAME的伙伴们，一起来支持STARNAME吧？微信小程序搜索：APPNAME，夺取冠军福利，就等
        //     你了。现在STARNAME排名第STARRANK，获得了STARSCORE票。@APPNAME";

        $text = str_replace('STARNAME[超话]', $user['user_star']['star']['chaohua'] . '[超话]', $text);
        $text = str_replace('STARNAME', $user['user_star']['star']['name'], $text);

        $text = str_replace('STARSCORE', $user['user_star']['star']['star_rank']['week_hot'], $text);
        $text = str_replace('STARRANK', $rank, $text);
        $text = str_replace('APPNAME', Cfg::getCfg('appname'), $text);

        Common::res(['data' => [
            'share_text' => $text,
            'weibo_zhuanfa' => Cfg::getCfg('weibo_zhuanfa'),
        ]]);
    }


}
