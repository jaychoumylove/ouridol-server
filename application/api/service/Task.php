<?php

namespace app\api\service;

use app\api\model\RecTask;
use app\api\model\Task as TaskModel;
use think\Db;
use app\api\model\UserStar;
use app\base\service\Common;
use app\api\model\RecStarChart;
use app\api\model\Rec;
use app\api\model\RecPayOrder;
use app\api\model\RecWeibo;
use app\api\model\UserRelation;

class Task
{

    /**检查任务是否完成 */
    public function checkTask($uid, $taskList)
    {
        // 已领取记录
        $recTask = RecTask::where(['user_id' => $uid])->whereTime('create_time', 'd')->column('task_id');
        foreach ($taskList as &$task) {
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
                    $task['doneTimes'] = Rec::where(['user_id' => $uid, 'type' => 1])->whereTime('create_time', 'd')->count();

                    if (in_array($task['id'], $recTask)) {
                        $task['status'] = 2;
                    } else {
                        if ($task['doneTimes'] >= $task['times']) {
                            $task['status'] = 1;
                        }
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
                        $isDone = RecWeibo::where(['user_id' => $uid])->whereTime('create_time', 'd')->find();
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
                        $isDone = UserRelation::where(['rer_user_id' => $uid])->where('status', 'neq', 3)->whereTime('create_time', 'd')->find();
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

        // $this->daily($task, $uid);

        Db::startTrans();
        try {
            RecTask::create([
                'task_id' => $task_id,
                'user_id' => $uid,
            ]);

            $update = [
                'coin' => $task['coin'],
                'stone' => $task['stone'],
                'trumpet' => $task['trumpet'],
            ];
            (new User())->change($uid, $update);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        return $update;
    }

    public function saveWeibo($weiboUrl, $uid, $text)
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
