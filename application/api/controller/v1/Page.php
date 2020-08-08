<?php

namespace app\api\controller\v1;

use app\api\model\CfgUserLevel;
use app\api\service\Sms;
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
use app\api\model\Hongbao;
use app\api\model\HongbaoUser;
use app\api\model\UserItem;
use GatewayWorker\Lib\Gateway;
use app\api\model\UserExt;
use app\api\model\Prop;
use app\api\model\UserProp;
use app\api\model\UserWxgroup;
use app\api\model\Wxgroup;
use app\api\model\WxgroupDynamic;
use app\api\service\User as ServiceUser;
use think\Db;
use app\api\model\ActiveFudai;
use app\api\model\ActiveFudaiUser;

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

        $res['userInfo'] = User::where(['id' => $this->uid])->field('id,nickname,avatarurl,type,phoneNumber')->find();
        $res['userCurrency'] = UserCurrency::getCurrency($this->uid);
        $res['userExt'] = UserExt::where('user_id', $this->uid)->field('is_join_wxgroup')->find();

        $userStar = UserStar::with('Star')->where(['user_id' => $this->uid])->order('id desc')->find();
        if (!$userStar) {
            $res['userStar'] = [];
            $res['userStar']['captain'] = 0;
        } else {
            $res['userStar'] = $userStar['star'];
            $res['userStar']['captain'] = $userStar['captain'];
//             // 二维码
//             if (!$userStar['qrcode']) {
//                 // 获取二维码
//                 $data = (new WxAPI())->getwxacode('/pages/index/index?starid=' . $userStar['star_id'] . '&referrer=' . $this->uid);
//                 if (!isset($data['errcode'])) {
//                     // 上传图片并保存
//                     $filePath = ROOT_PATH . 'public/uploads/qrcode.jpg';
//                     file_put_contents($filePath, $data);
//                     $file = (new WxAPI('gzh'))->addMaterial($filePath);
//                     if (!isset($file['errcode'])) {
//                         try {
//                             unlink($filePath);
//                         } catch (\Throwable $th) {
//                             //throw $th;
//                         }
//                         $res['qrcode'] = str_replace('http', 'https', $file['url']);
//                         UserStar::where(['user_id' => $this->uid])->update([
//                             'qrcode' => $res['qrcode']
//                         ]);
//                     }
//                 }
//             } else {
//                 $res['qrcode'] = $userStar['qrcode'];
//             }
        }

        // 顺便获取分享信息
        $res['config'] = Cfg::getList();
        if ($userStar) {
            if($userStar['star']['birthday'] && $userStar['star']['birthday'] == (int) date('md') && $userStar['star']['head_img_s']){
                // 生日弹框条件
                $res['config']['index_banner']['img_url'] = $userStar['star']['head_img_s'];
                $res['config']['isBirthday']=1;
            }

        }
        $res['config']['share_text'] = CfgShare::getOne();
        $res['spriteInfo'] = UserSprite::getInfo($this->uid, $this->uid);
        $spriteUpgrade = $res['spriteInfo']['need_stone'];
        $stone = UserCurrency::where(['uid' => $this->uid])->value('stone');

        if ($stone >= $spriteUpgrade && $res['spriteInfo']['sprite_level'] < 30) {
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
        $res['fudai'] = ActiveFudai::checkFudai();
        $res['userRank'] = UserStar::getRank($starid, 'thisweek_count', 1, 5);
        $res['captain'] = UserStar::where('user_id', $this->uid)->value('captain');
        // 聊天内容
        $res['chartList'] = RecStarChart::getLeastChart($starid, $this->uid);
        // 加入聊天室
        Gateway::joinGroup($client_id, 'star_' . $starid);
        $res['disLeastCount'] = AppStar::disLeastCount($starid);

        $res['mass'] = ShareMass::getMass($this->uid);

        $count = UserStar::where('user_id', $this->uid)->order('id desc')->value('total_count');
        $res['my_level'] = CfgUserLevel::where('total', '<=', $count)->max('level');

        //是否自动偷能量
        $userExtInfo = UserExt::where('user_id', $this->uid)->field('is_automatic_steal,left_time')->find();
        $res['is_automatic_steal'] = $userExtInfo['is_automatic_steal'];
        $left_time = json_decode($userExtInfo['left_time'], true);
        $res['stealTime'] = max($left_time);

        $res['article'] = Article::where('1=1')->order('create_time desc,id desc')->find();

        // 师傅收益
        // $cur_contribute = UserFather::where(['father' => $this->uid])->max('cur_contribute');
        // $res['fatherEarn'] = floor($cur_contribute * Cfg::getCfg('father_earn_per'));

        // 应援解锁
        // $res['activeInfo'] = UserStar::getActiveInfo($this->uid, $starid);
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
        Common::res(['data' => Prop::where(['status' => Prop::ON])->order('sort','asc')->select()]);
    }

    public function myprop()
    {
        $this->getUser();
        $page = input ('page', 1);
        $size = input ('size', 10);
        $res['list'] = UserProp::getList($this->uid, $page, $size);
        Common::res(['data' => $res]);
    }

    /**游戏试玩 */
    public function game()
    {
        $type = $this->req('type', 'integer', 0);
        $w = ['platform' => input('platform')];
        if ($type == 1) {
            $w['show'] = 1;
        }
        Common::res([
            'data' => CfgAds::where($w)->order('sort asc')->select()
        ]);
    }

    /**群集结信息 */
    public function groupMass()
    {
        $gid = $this->req('gid', 'integer');
        $star_id = $this->req('star_id', 'integer');

        UserWxgroup::massSettle();

        $res = UserWxgroup::massStatus($gid);
        // 集结成员
        if ($res['status'] != 0) {
            $res['list'] = UserWxgroup::with('User')->where('wxgroup_id', $gid)
                ->whereTime('mass_join_at', 'between', [$res['massStartTime'], $res['massEndTime']])->order('mass_join_at asc')->select();
        } else {
            $res['list'] = [];
        }
        // star信息
        $res['star'] = AppStar::where('id', $star_id)->field('name,head_img_s')->find();
        Common::res(['data' => $res]);
    }

    public function wxgroup()
    {
        $this->getUser();
        // 集结动态
        $res['dynamic'] = array_reverse(WxgroupDynamic::where('1=1')->order('id desc')->limit(30)->select());

        // 群日贡献排名
        $res['groupList'] = Wxgroup::with('star')->order('thisday_count desc')->limit(10)->select();
        foreach ($res['groupList'] as &$group) {
            $group['userRank'] = UserWxgroup::with('user')->where('wxgroup_id', $group['id'])->order('thisday_count desc')->field('user_id,thisday_count')->limit(5)->select();
        }

        // 贡献奖励
        $res['reback'] = UserWxgroup::where('user_id', $this->uid)->sum('daycount_reback');

        Common::res(['data' => $res]);
    }

    public function hongbao()
    {
        $this->getUser();

        $latest = Hongbao::where('user_id', $this->uid)->order('id desc')->find();
        $res['send'] = 3600 - (time() - strtotime($latest['create_time']));
        Common::res(['data' => $res]);
    }

    /**发红包 */
    public function sendHongbao()
    {
        $this->getUser();

        $hongbaoId = Hongbao::sendbox($this->uid);
        Common::res(['data' => $hongbaoId]);
    }

    public function getBox()
    {
        $referrer = $this->req('referrer', 'integer');
        $box_id = Hongbao::where('user_id', $referrer)->max('id');

        $this->getUser();

        $res['open'] = HongbaoUser::openBox($this->uid, $box_id);

        $res['info'] = Hongbao::with('user')->where('id', $box_id)->find();

        $res['self'] = HongbaoUser::with('user')->where('box_id', $box_id)->where('user_id', $this->uid)->find();
        if (!$res['self']) $res['self'] = ['count' => 0];

        $res['list'] = HongbaoUser::with('user')->where('box_id', $box_id)->order('id desc')->select();
        // 已领取
        $res['info']['isEarn'] = HongbaoUser::where('box_id', $box_id)->sum('count');
        // 手气最佳
        $res['lucky'] = HongbaoUser::where('box_id', $box_id)->order('count desc')->value('user_id');
        // // 奖品type 1coin
        // $res['award_type'] = RecLottery::with(['lottery'])->where('id', $box_id)->find()['lottery']['type'];

        Common::res(['data' => $res]);
    }

    public function getHongbaoDouble()
    {
        $this->getUser();
        HongbaoUser::getDouble($this->uid);

        Common::res();
    }

    /**
     * 我的福袋列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function fudai ()
    {
        $page = $this->req('page', 'require');
        $this->getUser();

        $today = date('Y-m-d') . " 00:00:00";

        //opened_people,status
        $res = (new ActiveFudai)->where('user_id', $this->uid)
            ->where('create_time', '>', $today)
            ->field("*, finished as status, receive as opened_people")
            ->order('finished asc, create_time desc')
            ->page($page, 10)
            ->select();

        Common::res(['data' => $res]);
    }

    /*发福袋 */
    public function sendFudai()
    {
        $this->getUser();

        $fudaiId = ActiveFudai::sendbox($this->uid);
        Common::res(['data' => $fudaiId]);
    }

    /*开福袋*/
    public function getFudai()
    {
        $box_id = $this->req('id', 'integer');

        $this->getUser();

        $res['open'] = ActiveFudaiUser::openBox($this->uid, $box_id);

        $res['info'] = ActiveFudai::with('user')->where('id', $box_id)->find();

        $res['self'] = ActiveFudaiUser::with('user')->where('box_id', $box_id)->where('user_id', $this->uid)->find();
        if (!$res['self']) $res['self'] = ['count' => 0];

        $res['list'] = ActiveFudaiUser::with('user')->where('box_id', $box_id)->order('id desc')->select();
        // 已领取
        $res['info']['isEarn'] = ActiveFudaiUser::where('box_id', $box_id)->sum('count');
        // 手气最佳
        $res['lucky'] = ActiveFudaiUser::where('box_id', $box_id)->order('count desc')->value('user_id');
        // // 奖品type 1coin
        // $res['award_type'] = RecLottery::with(['lottery'])->where('id', $box_id)->find()['lottery']['type'];

        Common::res(['data' => $res]);
    }

    /*开福袋双倍*/
    public function getFudaiDouble()
    {
        $this->getUser();
        ActiveFudaiUser::getDouble($this->uid);

        Common::res();
    }

    public function redress()
    {
        $this->getUser();

        $res = UserExt::redress($this->uid);
        Common::res(['data' => $res]);
    }
    public function sendSms()
    {

        $phoneNumber = input('phoneNumber',0);
        $this->getUser();

        $phoneNumber = strpos($phoneNumber,'86')!==false && strpos($phoneNumber,'86')==0 ? substr($phoneNumber, -11) : $phoneNumber;
        $hasExist = User::where('phoneNumber',$phoneNumber)->count();
        if($hasExist) Common::res(['code' => 1, 'msg' => '该号码已被占用']);

        $sms = json_decode(UserExt::where('user_id',$this->uid)->value('sms'),true);
        if(isset($sms['phoneNumber']) && time()-$sms['sms_time']<=24*3600 && $sms['phoneNumber']==$phoneNumber ) Common::res(['code' => 1, 'msg' => '验证码已发送，1天只能发送一次']);

        $content = (new Sms())->send($phoneNumber);
        UserExt::where('user_id',$this->uid)->update(['sms'=>json_encode($content)]);
        if($content['Code'] != 'OK') Common::res(['code' => 1, 'msg' => $content['Message']]);
        Common::res();
    }
}
