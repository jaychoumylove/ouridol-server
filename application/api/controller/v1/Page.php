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
use app\api\model\CfgAds;
use app\api\model\UserSprite;
use app\base\service\WxAPI;
use app\api\model\CfgItem;
use app\api\model\UserItem;
use GatewayWorker\Lib\Gateway;
use app\api\model\UserExt;
use app\api\model\Prop;
use app\api\model\UserProp;

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
        $res['userExt'] = UserExt::where('user_id', $this->uid)->field('is_join_wxgroup')->find();

        $userStar = UserStar::with('Star')->where(['user_id' => $this->uid])->order('id desc')->find();
        if (!$userStar) {
            $res['userStar'] = [];
        } else {
            $res['userStar'] = $userStar['star'];
            // // 二维码
            // if (!$userStar['qrcode']) {
            //     // 获取二维码
            //     $data = (new WxAPI())->getwxacode('/pages/index/index?starid=' . $userStar['star_id'] . '&referrer=' . $this->uid);
            //     if (!isset($data['errcode'])) {
            //         // 上传图片并保存
            //         $filePath = ROOT_PATH . 'public/uploads/qrcode.jpg';
            //         file_put_contents($filePath, $data);
            //         $file = (new WxAPI('gzh'))->addMaterial($filePath);
            //         if (!isset($file['errcode'])) {
            //             try {
            //                 unlink($filePath);
            //             } catch (\Throwable $th) {
            //                 //throw $th;
            //             }
            //             $res['qrcode'] = str_replace('http', 'https', $file['url']);
            //             UserStar::where(['user_id' => $this->uid])->update([
            //                 'qrcode' => $res['qrcode']
            //             ]);
            //         }
            //     }
            // } else {
            //     $res['qrcode'] = $userStar['qrcode'];
            // }
        }

        // 顺便获取分享信息
        $res['config'] = Cfg::getList();
        $res['config']['share_text'] = CfgShare::getOne();

        $spriteUpgrade = UserSprite::getInfo($this->uid, $this->uid)['need_stone'];
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
        UserExt::checkSteal($this->uid);

        $res['starInfo'] = AppStar::with('StarRank')->where(['id' => $starid])->find();

        $starService = new Star();
        $res['starInfo']['star_rank']['week_hot_rank'] = $starService->getRank($res['starInfo']['star_rank']['week_hot'], 'week_hot');

        $res['userRank'] = UserStar::getRank($starid, 'thisweek_count', 1, 5);
        $res['captain'] = UserStar::where('user_id', $this->uid)->value('captain');
        // 聊天内容
        $res['chartList'] = RecStarChart::getLeastChart($starid);
        // 加入聊天室
        Gateway::joinGroup($client_id, 'star_' . $starid);

        $res['mass'] = ShareMass::getMass($this->uid);

        // $res['invitList'] = [
        //     'list' => UserRelation::fixByType(1, $this->uid, 1, 10),
        //     'award' => Cfg::getCfg('invitAward'),
        //     'hasInvitcount' => UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['in', [1, 2]]])->count()
        // ];

        $res['article'] = Article::where('1=1')->order('create_time desc,id desc')->find();

        // 师傅收益
        // $cur_contribute = UserFather::where(['father' => $this->uid])->max('cur_contribute');
        // $res['fatherEarn'] = floor($cur_contribute * Cfg::getCfg('father_earn_per'));

        // 应援解锁
        $res['activeInfo'] = UserStar::getActiveInfo($this->uid, $starid);
        // $res['active_info'] = Cfg::getCfg('active_info');
        // $res['activeInfo']['complete_people'] = UserStar::where(['star_id' => $starid])->sum('active_card_days');
        // $res['activeInfo']['nextCount'] = '完成解锁';
        // foreach ($res['active_info'] as $value) {
        //     if ($res['activeInfo']['complete_people'] < $value['count']) {
        //         // 下一目标次数与金额
        //         $res['activeInfo']['nextCount'] = $value['count'];
        //         $res['activeInfo']['nextFee'] = $value['fee'];
        //         break;
        //     } else {
        //         // 已达成次数与金额
        //         $res['activeInfo']['finishedCount'] = $value['count'];
        //         $res['activeInfo']['finishedFee'] = $value['fee'];
        //     }
        // }

        // 礼物
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
            if (!$value['self']) $value['self'] = 0;
        }

        Common::res(['data' => $res]);
    }

    public function giftPackage()
    {
        $this->getUser();
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
            if (!$value['self']) $value['self'] = 0;
        }

        Common::res(['data' => $res]);
    }

    public function giftCount()
    {
        $this->getUser();
        $res = UserItem::where(['uid' => $this->uid])->sum('count');
        Common::res(['data' => $res]);
    }

    public function prop()
    {
        Common::res(['data' => Prop::all()]);
    }

    public function myprop()
    {
        $this->getUser();
        $res['list'] = UserProp::getList($this->uid);
        Common::res(['data' => $res]);
    }

    /**游戏试玩 */
    public function game()
    {
        $type = $this->req('type', 'integer', 0);
        if ($type == 1) {
            $w = ['show' => 1];
        } else {
            $w = '1=1';
        }
        Common::res(['data' => CfgAds::where($w)->order('sort asc')->select()]);
    }
}
