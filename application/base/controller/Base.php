<?php
namespace app\base\controller;

use think\Controller;
use app\base\service\Common;
use app\api\model\Cfg;

class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $version = input('version');
    }

    /**获取访问用户uid */
    protected function getUser()
    {
        $token = input('token');
        if (!$token) Common::res(['code' => 200]);
        $this->uid = Common::getSession($token);
        if (!$this->uid) Common::res(['code' => 201]);
    }
    
}
