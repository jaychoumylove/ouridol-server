<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\RecUserFormid;
use app\base\service\Common;
use app\api\model\CfgShare;
use app\api\model\Cfg;
use app\api\model\UserStar;
use think\Db;
use app\base\service\WxAPI;
use app\api\model\Funclub;
use app\api\model\Rec;

class Ext extends Base
{

    public function saveFormId()
    {
        $formId = input('formId');
        if (!$formId) Common::res(['code' => 100]);
        $this->getUser();

        RecUserFormid::create([
            'user_id' => $this->uid,
            'form_id' => $formId,
        ]);
        Common::res([]);
    }

    public function config()
    {
        $key = input('key');
        if ($key) Common::res(['data' => Cfg::getCfg($key)]);
        
        // 顺便获取分享信息
        $res['share_text'] = CfgShare::getOne();

        $list = Cfg::all(['show' => 1]);
        foreach ($list as $value) {
            $val = json_decode($value['value'], true);
            if (!$val) $val = $value['value'];

            $res[$value['key']] = $val;
        }
        Common::res(['data' => $res]);
    }

    /**活动 明星信息 */
    public function getActiveInfo()
    {
        $starid = input('starid');
        $this->getUser();

        // 活动信息
        $res['active_info'] = Cfg::getCfg('active_info');
        // 参与人数
        $res['join_people'] = UserStar::where(['star_id' => $starid])->where('active_card_days', '>', 0)->count();
        // 完成人数
        $res['complete_people'] = UserStar::where(['star_id' => $starid])->where('active_card_days', '>=', 7)->count();


        $active_card = UserStar::where(['user_id' => $this->uid, 'star_id' => $starid])->field('active_card_days,active_card_time')->find();
        // 今日是否已打卡
        $res['can_card'] = date('ymd', time()) != date('ymd', $active_card['active_card_time']);
        // 我的累计打卡
        $res['my_card_days'] = $active_card['active_card_days'] ? $active_card['active_card_days'] : 0;

        Common::res(['data' => $res]);
    }

    /**活动 打卡 */
    public function setCard()
    {
        $this->getUser();
        $active_card_time = UserStar::where(['user_id' => $this->uid])->value('active_card_time');
        if (date('ymd', time()) == date('ymd', $active_card_time)) {
            Common::res(['code' => 1, 'msg' => '你今天已经打卡了哦']);
        }
        UserStar::where(['user_id' => $this->uid])->update([
            'active_card_days' => Db::raw('active_card_days+1'),
            'active_card_time' => time()
        ]);

        Common::res([]);
    }

    public function userRank()
    {
        $starid =  input('starid');
        $page =  input('page', 1);
        $size =  input('size', 10);

        $list = UserStar::with('User')->where(['star_id' => $starid])->where('active_card_days', '>', 0)
            ->order('active_card_days desc')->page($page, $size)->select();

        Common::res(['data' => $list]);
    }

    public function upload()
    {
        $file = request()->file('file');

        // $dir = iconv("UTF-8", "GBK", ROOT_PATH . 'public/upload');
        // if (!file_exists($dir)) {
        //     mkdir($dir, 0777, true);
        // } 

        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {

                $realPath = ROOT_PATH . 'public' . DS . 'uploads' . DS . $info->getSaveName();
                $res = (new WxAPI('gzh'))->addMaterial($realPath);
                unlink($realPath);
                Common::res(['data' => $res]);
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    /**
     * 后援会入住
     */
    public function funclubJoin()
    {
        $this->getUser();

        $find = Funclub::where(['user_id' => $this->uid])->find();
        if ($find) Common::res(['code' => 1, 'msg' => '请勿重复提交']);

        $res['user_id'] = $this->uid;
        $res['avatar'] = input('avatar');
        $res['clubname'] = input('clubname');
        $res['name'] = input('name');
        $res['post'] = input('post');
        $res['wx'] = input('wx');
        $res['qualify'] = input('qualify');

        Funclub::create($res);
        Common::res([]);
    }

    public function log()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);

        $logList = Rec::with('Type,TargetUser,TargetStar')->where(['user_id' => $this->uid])->order('id desc')->page($page, $size)->select();

        Common::res(['data' => $logList]);
    }
}
