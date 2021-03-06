<?php

namespace app\api\controller\v1;

use app\api\model\CfgSprite;
use app\api\model\UserSpriteBg;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\api\model\UserSprite as UserSpriteModel;
use app\base\service\Common;
use app\api\service\User;
use think\Db;

class UserSprite extends Base
{
    public function info()
    {
        $user_id = input('user_id');
        if (!$user_id) Common::res(['code' => 100]);
        $this->getUser();
        $res = UserSpriteModel::getInfo($user_id, $this->uid);
        Common::res(['data' => $res]);
    }

    public function settle()
    {
        $user_id = input('user_id');
        if (!$user_id) Common::res(['code' => 100]);
        $this->getUser();

        $earn = UserSpriteModel::settle($user_id, $this->uid);

        if ($user_id != $this->uid) {

            Common::res(['code' => 1, 'msg' => '不能帮别人收取能量了']);
            // 为别人收能量
//            $myEarn = UserSpriteModel::getTip($earn, $this->uid);
//            Common::res(['data' => $myEarn]);
        } else {
            Common::res(['data' => $earn]);
        }
    }

    public function shortEarn()
    {
        $this->getUser();

        $info = UserSpriteModel::getInfo($this->uid, $this->uid);

        if ($info['isUseCard']) {
            (new User())->change($this->uid, [
                'coin' => $info['earnPer']
            ]);
            UserSpriteModel::where('user_id', $this->uid)->update([
                'total_coin' => Db::raw('total_coin+' . $info['earnPer'])
            ]);
        }

        Common::res([
            'data' => [
                'isUseCard' => $info['isUseCard'],
                'earn' => $info['earnPer']
            ]
        ]);
    }

    public function rankList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $rankField = input('rankField', 'thisday_coin');

        $res = UserSpriteModel::getRankList($this->uid, $page, $size ,$rankField);

        Common::res(['data' => $res]);
    }

    public function zanGod()
    {
        $this->getUser();
        $user_id = input('user_id');
        if ($this->uid == $user_id) Common::res(['code' => 1, 'msg' => '您有点自恋，不能膜拜自己！']);
        $data['earn_coin'] = 1000;
        $data['god_earn_coin'] = 10;
        $data['cover_count'] = UserSpriteModel::zanGod($this->uid, $user_id, $data['earn_coin'], $data['god_earn_coin']);

        Common::res(['data' => $data]);
    }

    public function upgrade()
    {
        $this->getUser();
        $type = input('type', 0);

        UserSpriteModel::upgrade($this->uid, $type);
        Common::res();
    }

    public function skill()
    {
        $type = input('type', 1);
        $skillList = UserSpriteModel::getSkill($type);
        Common::res(['data' => $skillList]);
    }

    //图鉴
    public function getHandBook()
    {
        $this->getUser();
        $res = CfgSprite::group('image')->order('id asc')->select();
        $star_id = UserStar::where('user_id',$this->uid)->value('star_id');
        if($star_id!=42){
            unset($res[1]);
        }
        Common::res(['data' => $res]);
    }

    //换肤
    public function switchImage()
    {
        $this->getUser();
        $level = input('level');
        if ($level<=0) Common::res(['code' => 100]);
        UserSpriteModel::switchImage($this->uid, $level);
        Common::res();
    }

    //精灵背景上传头像
    public function sprite_bg_upload_img()
    {
        $this->getUser();
        $id = $this->req('upload_id', 'integer','0');
        $avatar = $this->req('avatar', 'require');

        UserSpriteBg::uploadImg($this->uid,$id,$avatar);
        Common::res([]);
    }


}
