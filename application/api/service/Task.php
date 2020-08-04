<?php

namespace app\api\service;

use app\api\model\CfgAds;
use app\api\model\RecTask;
use app\api\model\Task as TaskModel;
use app\api\model\UserTreasureBox;
use think\Db;
use app\api\model\UserStar;
use app\base\service\Common;
use app\api\model\RecStarChart;
use app\api\model\Rec;
use app\api\model\RecPayOrder;
use app\api\model\RecWeibo;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\RecItem;
use app\api\model\UserSprite;

class Task
{

    /**检查任务是否完成 */
    public function checkTask($uid, $category)
    {
        $taskList = TaskModel::with('TaskType')->where('category', $category)->order('sort asc')->select();
        // 已领取记录
        if ($category == 0) {
            // 新手任务只完成一次
            $recTask = RecTask::where(['user_id' => $uid])->where('task_category', $category)->column('task_id');
        } else if ($category == 1) {
            // 每日任务每天都可完成
            $recTask = RecTask::where(['user_id' => $uid])->where('task_category', $category)->whereTime('create_time', 'd')->column('task_id');
        }

        foreach ($taskList as $key => &$task) {
            $task['status'] = 0;
            // 检查完成状态
            switch ($task['type']) {
                case 1:
                    // 每日签到
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $task['status'] = 1;
                    }
                    break;
                case 2:
                    // 每日打榜
                    $task['doneTimes'] = Rec::where(['user_id' => $uid, 'type' => 2])->whereTime('create_time', 'd')->sum('coin') / -1;
                    $task['doneTimes'] += RecItem::where(['user_id' => $uid])->whereTime('create_time', 'd')->sum('valueof');
                    // 礼物
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 3:
                    // 偷能量
                    $task['doneTimes'] = 0;
                    $steal = UserExt::where(['user_id' => $uid])->field('steal_times,steal_time')->find();

                    if (date('Ymd', $steal['steal_time']) == date('Ymd', time())) {
                        $task['doneTimes'] = $steal['steal_times'];
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    }
                    break;
                case 4:
                    // 每日充值
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = RecPayOrder::where(['user_id' => $uid])->where('pay_time', 'not null')->whereTime('create_time', 'd')->find();
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 5:
                    // 喊话
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = Rec::where(['user_id' => $uid, 'type' => 3])->whereTime('create_time', 'd')->value('id');
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }

                    break;
                case 6:
                    // 圈内留言
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = RecStarChart::where(['user_id' => $uid])->whereTime('create_time', 'd')->find();
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 7:
                    // 观看广告
                    if (isset(array_count_values($recTask)[$task['id']])) {
                        $task['doneTimes'] = array_count_values($recTask)[$task['id']];
                    } else {
                        $task['doneTimes'] = 0;
                    }
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 2;
                    }

                    break;
                case 8:
                    // 微博发帖
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = RecWeibo::where(['user_id' => $uid, 'type' => 0])->whereTime('create_time', 'd')->find();
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 9:
                    // 邀请好友
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = UserRelation::where(['rer_user_id' => $uid])->where('status', 'in', [1, 2])->whereTime('create_time', 'd')->find();
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 10:
                    // 助人为乐
                    if ($task['id'] == 13) {
                        // 助人为乐获得1000能量
                        $task['doneTimes'] = Rec::where(['type' => 4, 'user_id' => $uid])->whereTime('create_time', 'd')->sum('coin');
                    } else {
                        $task['doneTimes'] = Rec::where(['type' => 4, 'user_id' => $uid])->whereTime('create_time', 'd')->count();
                    }

                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 11:
                    // 领取徒弟收益
                    if ($task['id'] == 16) {
                        $task['doneTimes'] = Rec::where(['type' => 5, 'user_id' => $uid])->whereTime('create_time', 'd')->sum('coin');
                    } else {
                        $task['doneTimes'] = Rec::where(['type' => 5, 'user_id' => $uid])->whereTime('create_time', 'd')->count();
                    }

                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 12:
                    // 成功集结
                    $task['doneTimes'] = Rec::where(['type' => 6, 'user_id' => $uid])->whereTime('create_time', 'd')->count();

                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 13:
                    // 公众号签到
                    $signin_time = UserExt::where(['user_id' => $uid])->value('signin_time');
                    $isDone = date('Ymd', time()) == date('Ymd', $signin_time);

                    if ($isDone) {
                        $task['status'] = 2;
                    }
                    break;

                case 14:
                    // 微博转发
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = RecWeibo::where(['user_id' => $uid, 'type' => 1])->whereTime('create_time', 'd')->find();
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 15:
                    // 加好友
                    $task['doneTimes'] = UserRelation::where('rer_user_id', $uid)->where('status', 4)->whereTime('create_time', 'd')->count('id');
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 16:
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        $isDone = Rec::where(['type' => 25, 'user_id' => $uid])->whereTime('create_time', 'd')->count('id');
                        if ($isDone) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                case 17:
                    // 游戏试玩
                    // 用户贡献度大于200才显示游戏试玩
                    $userCount = UserStar::where('user_id', $uid)->order('total_count desc')->value('total_count');
                    if ($userCount < 201) {
                        unset($taskList[$key]);
                    } else {
                        $task['doneTimes'] = RecTask::where('user_id', $uid)->where('task_id', $task['id'])->whereTime('create_time', 'd')->count('id');

                        if ($task['doneTimes'] > $task['times']) {
                            $task['status'] = 2;
                        }
                    }
                    break;
                case 18:
                    // 偷能量数量
                    $task['doneTimes'] = 0;
                    $steal = UserExt::where(['user_id' => $uid])->field('steal_count,steal_time')->find();

                    if (date('Ymd', $steal['steal_time']) == date('Ymd', time())) {
                        $task['doneTimes'] = $steal['steal_count'];
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    }
                    break;
                case 19:
                    // 被开宝箱次数
                    $task['doneTimes'] = UserTreasureBox::where('user_id', $uid)->where('index', '>', 0)->whereTime('create_time', 'd')->count();
                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }


        return $taskList;
    }

    // 特殊：每日签到
    public function daily(&$task, $uid)
    {
        if ($task['type'] == 1) {
            $times = RecTask::where(['user_id' => $uid, 'task_id' => $task['id']])->whereTime('create_time', '-7 day')->column('id');
            $task['coin'] += count($times) * 20;
        }
    }

    /**奖励领取 */
    public function settle($task_id, $uid)
    {
        $task = TaskModel::get($task_id);

        $this->checkTaskId($uid,$task_id);

        // $this->daily($task, $uid);

        Db::startTrans();
        try {
            RecTask::create([
                'task_id' => $task_id,
                'user_id' => $uid,
                'task_category' => $task['category']
            ]);

            $update = [
                'coin' => $task['coin'],
                'stone' => $task['stone'],
                'trumpet' => $task['trumpet'],
            ];
            (new User())->change(
                $uid,
                $update,
                [
                    'type' => 12
                ]
            );

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        return $update;
    }

    /**领取时检查任务是否完成 */
    public function checkTaskId($uid, $task_id)
    {
        $task = TaskModel::with('TaskType')->where('id', $task_id)->find();
        // 已领取记录
        if ($task['category'] == 0) {
            // 新手任务只完成一次
            $recTask = (new RecTask)->readMaster()->where(['user_id' => $uid])->where('task_id', $task_id)->count();
        } else if ($task['category'] == 1) {
            // 每日任务每天都可完成
            $recTask = (new RecTask)->readMaster()->where(['user_id' => $uid])->where('task_id', $task_id)->whereTime('create_time', 'd')->count();
        }

        $task['status'] = 0;
        // 检查完成状态
        switch ($task['type']) {
            case 1:
                // 每日签到
                // 每日签到
                if ($recTask) {
                    $task['status'] = 2;
                } else {
                    $task['status'] = 1;
                }
                break;
            case 2:
                // 每日打榜
                $task['doneTimes'] = (new Rec)->readMaster()->where(['user_id' => $uid, 'type' => 2])->whereTime('create_time', 'd')->sum('coin') / -1;
                $task['doneTimes'] += (new RecItem)->readMaster()->where(['user_id' => $uid])->whereTime('create_time', 'd')->sum('valueof');
                // 礼物
                if ($recTask) {
                    $task['status'] = 2;
                } else {
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }
                break;
            case 3:
                // 偷能量
                $task['doneTimes'] = 0;
                $steal = (new UserExt)->readMaster()->where(['user_id' => $uid])->field('steal_times,steal_time')->find();

                if (date('Ymd', $steal['steal_time']) == date('Ymd', time())) {
                    $task['doneTimes'] = $steal['steal_times'];
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }
                if ($recTask) {
                    $task['status'] = 2;
                }
                break;
            case 4:
                // 每日充值
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new RecPayOrder)->readMaster()->where(['user_id' => $uid])->where('pay_time', 'not null')->whereTime('create_time', 'd')->find();
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 5:
                // 喊话
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new Rec)->readMaster()->where(['user_id' => $uid, 'type' => 3])->whereTime('create_time', 'd')->value('id');
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 6:
                // 圈内留言
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new RecStarChart)->readMaster()->where(['user_id' => $uid])->whereTime('create_time', 'd')->find();
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 7:
                // 观看广告

                if ($recTask >= $task['times']) {
                    $task['status'] = 2;
                }else{
                    $task['status'] = 1;
                }

                break;
            case 8:
                // 微博发帖
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new RecWeibo)->readMaster()->where(['user_id' => $uid, 'type' => 0])->whereTime('create_time', 'd')->find();
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 9:
                // 邀请好友
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new UserRelation)->readMaster()->where(['rer_user_id' => $uid])->where('status', 'in', [1, 2])->whereTime('create_time', 'd')->find();
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 10:
                // 助人为乐
                if ($task['id'] == 13) {
                    // 助人为乐获得1000能量
                    $task['doneTimes'] = (new Rec)->readMaster()->where(['type' => 4, 'user_id' => $uid])->whereTime('create_time', 'd')->sum('coin');
                } else {
                    $task['doneTimes'] = (new Rec)->readMaster()->where(['type' => 4, 'user_id' => $uid])->whereTime('create_time', 'd')->count();
                }

                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }


                break;
            case 11:
                // 领取徒弟收益
                if ($task['id'] == 16) {
                    $task['doneTimes'] = (new Rec)->readMaster()->where(['type' => 5, 'user_id' => $uid])->whereTime('create_time', 'd')->sum('coin');
                } else {
                    $task['doneTimes'] = (new Rec)->readMaster()->where(['type' => 5, 'user_id' => $uid])->whereTime('create_time', 'd')->count();
                }

                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 12:
                // 成功集结
                $task['doneTimes'] = (new Rec)->readMaster()->where(['type' => 6, 'user_id' => $uid])->whereTime('create_time', 'd')->count();

                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 13:
                // 公众号签到
                $signin_time = (new UserExt)->readMaster()->where(['user_id' => $uid])->value('signin_time');
                $isDone = date('Ymd', time()) == date('Ymd', $signin_time);

                if ($isDone) {
                    $task['status'] = 2;
                }
                break;

            case 14:
                // 微博转发
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new RecWeibo)->readMaster()->where(['user_id' => $uid, 'type' => 1])->whereTime('create_time', 'd')->find();
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 15:
                // 加好友
                $task['doneTimes'] = (new UserRelation)->readMaster()->where('rer_user_id', $uid)->where('status', 4)->whereTime('create_time', 'd')->count('id');
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 16:
                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    $isDone = (new Rec)->readMaster()->where(['type' => 25, 'user_id' => $uid])->whereTime('create_time', 'd')->count('id');
                    if ($isDone) {
                        $task['status'] = 1;
                    }
                }

                break;
            case 17:
                // 游戏试玩
                // 用户贡献度大于200才显示游戏试玩
                $userCount = (new UserStar)->readMaster()->where('user_id', $uid)->order('total_count desc')->value('total_count');
                if ($userCount < 201) {
                    Common::res(['code' => 1, 'msg' => '不能领取']);
                } else {
                    $task['doneTimes'] = (new RecTask)->readMaster()->where('user_id', $uid)->where('task_id', $task['id'])->whereTime('create_time', 'd')->count('id');

                    $task['times'] = CfgAds::where('platform','MP-WEIXIN')->order('sort asc')->count();
                    if ($task['doneTimes'] <= $task['times']) {
                        $task['status'] = 1;
                    }else{
                        $task['status'] = 2;
                    }
                }
                break;
            case 18:
                // 偷能量数量
                $task['doneTimes'] = 0;
                $steal = (new UserExt)->readMaster()->where(['user_id' => $uid])->field('steal_count,steal_time')->find();

                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    if (date('Ymd', $steal['steal_time']) == date('Ymd', time())) {
                        $task['doneTimes'] = $steal['steal_count'];
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
                    }
                }

                break;
            case 19:
                // 被开宝箱次数
                $task['doneTimes'] = (new UserTreasureBox)->readMaster()->where('user_id', $uid)->where('index', '>', 0)->whereTime('create_time', 'd')->count();

                if ($recTask) {
                    $task['status'] = 2;
                }else{
                    if ($task['doneTimes'] >= $task['times']) {
                        $task['status'] = 1;
                    }
                }

                break;
            default:
                # code...
                break;
        }

        if($task['status']==0){
            Common::res(['code' => 1, 'msg' => '还未完成该任务']);
        }elseif ($task['status']==2){
            Common::res(['code' => 1, 'msg' => '你已经领取过了，不能再领取了']);
        }

    }

    public function saveWeibo($weiboUrl, $uid, $text, $type)
    {
        $weiboUrlExist = RecWeibo::get(['md5' => md5($weiboUrl)]);
        if ($weiboUrlExist) Common::res(['code' => 1, 'msg' => '该链接已经提交使用']);

        $weiboContent = Common::request($weiboUrl, false);
        $weiboContent = strpos($weiboContent, $text);
        if (!$weiboContent) {
            Common::res(['code' => 1, 'msg' => '微博超话内容格式不正确']);
        } else {
            RecWeibo::create([
                'user_id' => $uid,
                'url' => $weiboUrl,
                'md5' => md5($weiboUrl),
                'type' => $type,
            ]);
        }
    }

    // 获取微博url
    public function getWeiboUrl($weiboUrl)
    {
        $weiboAid = '';
        preg_match('/^https\:\/\/m\.weibo\.cn\/status\/(\d+)/i', $weiboUrl, $weiboAid1);
        preg_match('/^https\:\/\/m\.weibo\.cn\/\d+\/(\d+)/i', $weiboUrl, $weiboAid2);
        preg_match('/^https\:\/\/weibointl\.api\.weibo\.cn\/.+?weibo_id=(\d+)/i', $weiboUrl, $weiboAid3);
        if (isset($weiboAid1[1])) $weiboAid = $weiboAid1[1];
        elseif (isset($weiboAid2[1])) $weiboAid = $weiboAid2[1];
        elseif (isset($weiboAid3[1])) $weiboAid = $weiboAid3[1];

        if (!$weiboUrl || !$weiboAid) Common::res(['code' => 1, 'msg' => '微博链接不正确']);

        $weiboUrl = 'https://m.weibo.cn/status/' . $weiboAid;
        return $weiboUrl;
    }
}
