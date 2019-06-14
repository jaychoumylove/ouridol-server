<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\User;
use app\api\model\UserCurrency;
use app\api\model\UserStar;
use app\api\model\CfgShare;
use app\api\model\Cfg;
use app\base\service\Common;
use app\api\model\UserRelation;
use app\api\model\ShareMass;
use app\api\model\UserFather;
use app\api\service\Star;
use app\api\model\Star as AppStar;
use app\api\model\RecStarChart;
use app\api\model\Article;
use app\api\model\UserSprite;
use app\base\service\WxAPI;
use app\api\model\CfgItem;
use app\api\model\UserItem;
use GatewayWorker\Lib\Gateway;

class Page extends Base
{


    public function app()
    {
        $this->getUser();

        $rer_user_id = input('referrer', 0);
        if ($rer_user_id) {
            // 拉新关系
            UserRelation::saveNew($this->uid, $rer_user_id);
            // 加入集结
            $res['massUser'] = ShareMass::join($rer_user_id, $this->uid);
            // 师徒关系
            UserFather::join($rer_user_id, $this->uid);
        }

        $res['userInfo'] = User::where(['id' => $this->uid])->field('id,nickname,avatarurl,type')->find();
        $res['userCurrency'] = UserCurrency::getCurrency($this->uid);

        $userStar = UserStar::with('Star')->where(['user_id' => $this->uid])->order('id desc')->find();
        if (!$userStar) {
            $res['userStar'] = [];
        } else {
            $res['userStar'] = $userStar['star'];
            // 二维码
            if (!$userStar['qrcode']) {
                // 获取二维码
                $data = (new WxAPI())->getwxacode('/pages/index/index?starid=' . $userStar['star_id'] . '&referrer=' . $this->uid);

                if (!isset($data['errcode'])) {
                    // 上传图片并保存
                    $filePath = ROOT_PATH . 'public/uploads/qrcode.jpg';
                    file_put_contents($filePath, $data);
                    $file = (new WxAPI('gzh'))->addMaterial($filePath);
                    if (!isset($file['errcode'])) {
                        unlink($filePath);
                        $res['qrcode'] = str_replace('http', 'https', $file['url']);
                        UserStar::where(['user_id' => $this->uid])->update([
                            'qrcode' => $res['qrcode']
                        ]);
                    }
                }
            } else {
                $res['qrcode'] = $userStar['qrcode'];
            }
        }

        // 顺便获取分享信息
        $res['config'] = Cfg::getList();
        $res['config']['share_text'] = CfgShare::getOne();

        $spriteUpgrade = UserSprite::getInfo($this->uid)['need_stone'];
        $stone = UserCurrency::where(['uid' => $this->uid])->value('stone');

        if ($stone >= $spriteUpgrade) {
            $res['upSprite'] = true;
        }

        Common::res(['data' => $res]);
    }

    public function group()
    {
        $starid = input('starid');
        $client_id = input('client_id');
        $this->getUser();

        if (!$starid) Common::res(['code' => 100]);

        $res['starInfo'] = AppStar::with('StarRank')->where(['id' => $starid])->find();

        $starService = new Star();
        $res['starInfo']['star_rank']['week_hot_rank'] = $starService->getRank($res['starInfo']['star_rank']['week_hot'], 'week_hot');

        $res['userRank'] = UserStar::getRank($starid, 'thisweek_count', 1, 5);

        // 聊天内容
        $res['chartList'] = RecStarChart::getLeastChart($starid);
        // 加入聊天室
        Gateway::joinGroup($client_id, 'star_' . $starid);

        $res['mass'] = ShareMass::getMass($this->uid);

        $res['invitList'] = [
            'list' => UserRelation::fixByType(1, $this->uid, 1, 10),
            'award' => Cfg::getCfg('invitAward'),
            'hasInvitcount' => UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['in', [1, 2]]])->count()
        ];

        $res['article'] = Article::where('1=1')->order('create_time desc,id desc')->find();

        // 师傅收益
        $cur_contribute = UserFather::where(['father' => $this->uid])->max('cur_contribute');
        $res['fatherEarn'] = floor($cur_contribute * Cfg::getCfg('father_earn_per'));


        // 活动信息
        $res['activeInfo']['active_info'] = Cfg::getCfg('active_info');
        // 参与人数
        $res['activeInfo']['join_people'] = UserStar::where(['star_id' => $starid])->where('active_card_days', '>', 0)->count();
        // 完成人数
        $res['activeInfo']['complete_people'] = UserStar::where(['star_id' => $starid])->where('active_card_days', '>=', 7)->count();


        $active_card = UserStar::where(['user_id' => $this->uid, 'star_id' => $starid])->field('active_card_days,active_card_time')->find();
        // 今日是否已打卡
        $res['activeInfo']['can_card'] = date('ymd', time()) != date('ymd', $active_card['active_card_time']);
        // 我的累计打卡
        $res['activeInfo']['my_card_days'] = $active_card['active_card_days'] ? $active_card['active_card_days'] : 0;

        // 礼物
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
        }

        Common::res(['data' => $res]);
    }
}
