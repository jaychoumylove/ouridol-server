<?php


namespace app\api\model;


use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Request;
use think\Response;

class ActiveYingyuan extends Base
{
    const SUP = 'sup'; // 打卡
    const EXT = 'ext'; // 拉新助力

    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')
            ->bind ('nickname,avatarurl');
    }

    /**
     * @param string $type
     * @return string|true
     */
    public static function checkYingyuan($type = self::SUP)
    {
        $info = Cfg::getCfg (Cfg::ACTIVE_YINGYUAN);
        $date = date ('Y-m-d');

        if ($date < $info['date'][0]) {
            return '活动未开始';
        }

        if ($date > $info['date'][1]) {
            return '活动已结束';
        }

        $request = Request::instance ();
        $platform = $request->param ('platform');
        if (in_array ($platform, $info['platform']) == false) {
            return '平台不支持';
        }

        if ($type == self::EXT) {
            if ($date < $info['ext_time']) {
                return '补签暂未开始';
            }
        }

        return true;
    }

    /**
     * 应援打卡
     * @param        $starId
     * @param        $uid
     * @param string $type
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setCard($starId, $uid, $type = self::SUP)
    {
        $typeMap = [self::SUP, self::EXT];
        if (in_array ($type, $typeMap) == false) Common::res (['msg' => "打卡错误", 'code' => 1]);

        $exist = self::where ('star_id', $starId)
            ->where ('user_id', $uid)
            ->order ('id', 'asc')
            ->find ();

        if ($exist) {
            if ($type == self::SUP) {
                $time = $exist['sup_time'];
                $day = explode (' ', $time)[0];
                if ($day == date ('Y-m-d')) {
                    Common::res (['msg' => "今日打卡次数已用完", 'code' => 1]);
                }
            }

            $update = [
                'sup_num' => bcadd ($exist['sup_num'], 1)
            ];

            if ($type == self::SUP) {
                $update['sup_time'] = date ('Y-m-d H:i:s');
            }
            if ($type == self::EXT) {
                $update['sup_ext'] = bcadd ($exist['sup_ext'], 1);
            }

            self::where(['id' => $exist['id']])->update ($update);
        } else {
            self::create ([
                'user_id' => $uid,
                'star_id' => $starId,
                'sup_num' => $type == self::SUP ? 1: 0,
                'sup_ext' => $type == self::SUP ? 0: 1,
            ]);
        }

        // 打卡赢能量
        if ($type == self::SUP) {
            (new UserService())->change ($uid, ['coin' => 1000], ['type' => Rec::YINGYUAN]);
        }
    }

    public function fixCard()
    {
        $list = self::withTrashed()->select ();
        if (is_object ($list)) $list = $list->toArray ();
        $update = [];
        $delete = [];
        $updateId = [];

        foreach ($list as $item) {
            if (array_key_exists ($item['user_id'], $update)) {
                $update[$item['user_id']] = 2;
                $updateId[$item['user_id']] = $item['id'];
            } else {
                $update[$item['user_id']] = ($item['sup_num'] > 2) ? 2: 1;
                $updateId[$item['user_id']] = $item['id'];
            }
        }

        $udtNum = 0;
        foreach ($update as $key => $item) {
            $res = self::where ('user_id', $key)->update(['sup_num' => $item, 'sup_ext' => 0]);
            $udtNum += (int)$res;
        }

        $ids = array_column ($list, 'id');
        $updateIds = array_values ($updateId);
        $deleteIDs = array_diff ($ids, $updateIds);
        $deleteIDs = implode (',', $deleteIDs);

        return Response::create (compact ('delNum', 'udtNum', 'update', 'deleteIDs'), 'json');
    }

    /**
     * 应援详情
     * @param $starId
     * @param $uid
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getYingyuan($starId, $uid)
    {
        $info = Cfg::getCfg (Cfg::ACTIVE_YINGYUAN);

        $infoProgress = $info['progress'];

        $defaultSelf = [
            'sup_num' => 0,
            'sup_ext' => 0
        ];
        $progressing = [
            'done' => 0, // 当前进程
            'doing' => $infoProgress[1]['step'],// 下一步进程
        ];
        $reward = [
            'done' => 0, // 已解锁奖金
            'doing' => $infoProgress[1]['reward'], // 下一步解锁奖金
        ];

        $people = [
            'join_num' => 0,
            'finish_num' => 0,
        ];

        $is_today = false;

        // 拿到排名前30人的数据
        $list = self::where ('star_id', $starId)
            ->where('sup_num', '>', 0)
            ->order ('sup_num', 'desc')
            ->limit (bcsub ($info['people'], 1), 1)
            ->select ();

        // 参与人数
        $joinNum = self::where ('star_id', $starId)
            ->where('sup_num', '>', 0)
            ->count ();

        if ($joinNum) $people['join_num'] = $joinNum;

        if ($list) {
            // 参与人数要达到人数要求
            $minNum = $list[0]['sup_num'];
            $progressing['done'] = $minNum;

            // 获取下一阶段的奖励和进程
            $nextSteps = array_filter ($infoProgress, function ($item) use ($minNum) {
                 return $item['step'] > $minNum;
            });

            if (empty($nextSteps)) {
                // 已解锁最高奖励
                $end = $infoProgress[bcsub (count ($infoProgress), 1)];
                $progressing['doing'] = $end['step'];
                $reward['doing'] = $end['reward'];
            } else {
                $nextSteps = array_values ($nextSteps);

                $progressing['doing'] = $nextSteps[0]['step'];
                $reward['doing'] = $nextSteps[0]['reward'];
            }
        }
        if (empty($self)) {
            // 用户打卡贡献不再前30名内
            $self = self::where ('star_id', $starId)
                ->where ('user_id', $uid)
                ->where ('sup_num', '>', 0)
                ->find ();

            if (empty($self)) {
                // 用户没有贡献打卡
                $self = $defaultSelf;
            } else {
                $is_today = date ('Y-m-d') == explode (' ', $self['sup_time'])[0];
            }
        }

        $finishNum = self::where ('star_id', $starId)
            ->where('sup_num', '>=', $progressing['doing'])
            ->count ();

        if ($finishNum) $people['finish_num'] = $finishNum;

        $step = array_filter ($infoProgress, function($item) {
            return $item['step'] > 0;
        });

        $step = array_map(function ($item) use ($people, $progressing, $info) {
            if ($item['step'] < $progressing['doing']) {
                $item['percent'] = 100;
            }
            if ($item['step'] == $progressing['doing']) {
                $item['percent'] = bcdiv ($people['finish_num'], $info['people']);
            }
            if ($item['step'] > $progressing['doing']) {
                $item['percent'] = 0;
            }

            return $item;
        }, $step);

        array_values ($step);
        $sup_ext = date('Y-m-d') < $info['ext_time'] ? false: true;

        return compact ('self', 'reward', 'progressing', 'info', 'people', 'step', 'is_today', 'sup_ext');
    }

    protected function setSupTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
}