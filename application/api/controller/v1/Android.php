<?php


namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserStar;
use app\api\service\Star as StarService;
use app\base\controller\Base;
use app\base\service\Common;

class Android extends Base
{

    public function index()
    {
        return view('index');
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

    public function sendHot()
    {
        $starid = $this->req('starid', 'integer');
        $hot = $this->req('hot', 'integer');
        $hotOffset = mt_rand(1, floor($hot * 0.1));
        $hot += $hotOffset;

        $uid = User::getOneAndroid($starid);

        (new StarService())->sendHot($starid, $hot, $uid, 0, null);

        Common::res();
    }
}
