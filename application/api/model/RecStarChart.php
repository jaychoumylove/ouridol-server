<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;
use GatewayWorker\Lib\Gateway;
use app\base\service\WxAPI;

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
        $list = self::with(['User' => ['UserStar']])->where(['star_id' => $starid])->order('id desc')->limit(10)->select();

        // 粉丝等级
        // foreach ($list as &$value) {
        //     $totalCount = $value['user']['user_star']['total_count'];
        //     $value['level'] = CfgUserLevel::where('total', '<=', $totalCount)->max('level');
        // }

        return array_reverse($list);
    }

    /**
     * 敏感词汇校验
     * @return [0] 包含敏感词汇返回 true
     *         [1] 格式化后的内容
     */
    public static function verifyWord($text)
    {
        // $sensitiveWord = config('sensitive_word.words');
        // $flag = false;
        // foreach ($sensitiveWord as $word) {
        //     if (strpos($text, $word) !== false) {
        //         // 包含敏感词汇
        //         $flag = true;
        //         // 替换敏感词汇为*
        //         $symbol = '';
        //         for ($i = 0; $i < mb_strlen($word); $i++) $symbol .= '*';
        //         $text = str_replace($word, $symbol, $text);
        //     }
        // }

        // return [$flag, $text];

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
            $res['user'] = User::where(['id' => $uid])->field('nickname,avatarurl,type')->find();
            $res['user']['user_star'] = UserStar::get(['user_id' => $uid, 'star_id' => $starid]);

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
