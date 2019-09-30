<?php


namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserStar;
use app\api\service\Star as StarService;
use app\base\controller\Base;
use app\base\service\Common;
use think\Db;

class Android extends Base
{

    public function createView()
    {
        return view('create', [
            'active' => 'create'
        ]);
    }

    public function create()
    {
        $starid = $this->req('starid', 'integer');
        $nickname = $this->req('nickname', 'require');
        $avatar = $this->req('avatar', 'require');

        $uid = User::createAndroid($nickname, $avatar);
        UserStar::joinNew($starid, $uid);

        Common::res();
    }

    public function sendHotView()
    {
        return view('sendHot', [
            'active' => 'sendHot'
        ]);
    }

    public function sendHot()
    {
        $starid = $this->req('starid', 'integer');
        $hot = $this->req('hot', 'integer');
        $limit_hot = $this->req('limit_hot', 'integer');

        $hotOffset = mt_rand(1, floor($hot * 0.1));
        $hot += $hotOffset;

        $uid = User::getOneAndroid($starid, $limit_hot);

        (new StarService())->sendHot($starid, $hot, $uid, 0, null);

        Common::res();
    }

    public function infoView()
    {
        $starid = $this->req('starid');
        if ($starid) {
            $query = Db::query("SELECT u.nickname,u.avatarurl,s.thisweek_count FROM `f_user` u join f_user_star s on s.user_id = u.id where u.type = 5 and s.star_id = {$starid} ORDER BY thisweek_count desc;");
        } else {
            $query = [];
        }
        return view('info',[
            'active' => 'info',
            'list' => $query
        ]);
    }
}
