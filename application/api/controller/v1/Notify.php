<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\WxGzh;

class Notify extends Base
{

    public function receive()
    {

        echo input('echostr');
        $wxgzh = new WxGzh();
        $wxgzh->getMsg();
    }
}
