<?php
namespace app\api\controller\v1;

use app\base\service\WxMsg;
use app\base\controller\Base;

class Notify extends Base
{

    public function receive()
    {
        $wxMsg = new WxMsg();
        $wxMsg->checkSignature();
    }
}
