<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;
use GatewayWorker\Lib\Gateway;
use app\base\service\WxAPI;
use think\Log;

class RecStarChart extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    public static function getLeastChart($starid)
    {
        $list = self::with(['User' => [
            'UserStar' => function ($query) {
                $query->field('user_id,total_count,captain');
            },
            'UserExt' => function ($query) {
                $query->field('user_id,badge_id');
            }
        ]])->where(['star_id' => $starid])->order('id desc')->limit(10)->select();
        $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
        
        // 粉丝等级
        foreach ($list as &$value) {
            $totalCount = $value['user']['user_star']['total_count'];
            $value['user']['level'] = CfgUserLevel::where('total', '<=', $totalCount)->max('level');
        }

        return array_reverse($list);
    }

    /**
     * 敏感词汇校验
     * @return [0] 包含敏感词汇返回 true
     *         [1] 格式化后的内容
     */
    public static function verifyWord($text)
    {
        $res = (new WxAPI())->msgCheck($text);
        if ($res['errcode'] == 87014) Common::res(['code' => 1, 'msg' => '内容被屏蔽']);
    }

    /**留言 */
    public static function sendMsg($uid, $starid, $content)
    {
        // 校验
        self::verifyWord($content);

        Db::startTrans();
        try {
            // 保存聊天记录
            $res = self::create([
                'user_id' => $uid,
                'star_id' => $starid,
                'content' => $content,
                'create_time' => time(),
            ]);

            // 用户信息
            $res['user'] = User::with([
                'UserStar' => function ($query) {
                    $query->field('user_id,total_count,captain');
                },
                'UserExt' => function ($query) {
                    $query->field('user_id,badge_id');
                }
            ])->where('id', $uid)->field('id,nickname,avatarurl,type')->find();

            $totalCount = $res['user']['user_star']['total_count'];
            $res['user']['level'] = CfgUserLevel::where('total', '<=', $totalCount)->max('level');

            if ($res['user']['type'] == 2) {
                Db::rollback();
                Common::res(['code' => 1, 'msg' => '你已被禁言']);
            }

            // 推送socket消息
            Gateway::sendToGroup('star_' . $starid, json_encode([
                'type' => 'chartMsg',
                'data' => $res
            ], JSON_UNESCAPED_UNICODE));

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
    }
}
