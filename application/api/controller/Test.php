<?php

namespace app\api\controller;

use think\Controller;
use app\base\service\Common;
use app\api\model\StarRank;
use app\api\model\Star;
use think\Db;
use think\Log;
use think\Cache;
use app\base\service\WxAPI;
use think\Exception;
use app\base\controller\Base;
use app\base\service\WxMsg;
use app\api\model\PayOrder;
use app\api\model\RecPayOrder;
use app\api\model\Cfg;
use app\api\model\CfgLottery;
use app\api\model\RecStarChart;

class Test extends Base
{

    public function fanclub()
    { }

    public function getToken()
    {
        echo Common::setSession(input('uid') / 1234);
    }

    public function getUid()
    {
        echo Common::getSession(input('token'));
    }
    private function test()
    {
        return 1;
    }

    public function index()
    {
        dump((new WxAPI())->msgCheck('习大大前妻'));
        // $res = RecStarChart::verifyWord('1231313', true);

        // dump($res);
        // $lotteryList = CfgLottery::where('1=1')->order('chance asc')->select();
        // $totalPt = CfgLottery::where('1=1')->sum('chance');
        // $randPt = mt_rand(0, $totalPt);
        // $base = 0;
        // foreach ($lotteryList as $value) {
        //     if ($randPt < $value['chance'] + $base) {
        //         $lottery = $value;
        //         break;
        //     } else {
        //         $base += $value['chance'];
        //     }
        // }

        // Common::res(['data' => $lottery]);
        // echo strtotime('00:00:00');
        // Log::record(111);
        // Common::requestAsync('https://' . $_SERVER['SERVER_NAME'] . '/api/v1/auto/sendTmp',[
        //     'starid' => 35,
        //     'fee' => 100
        // ]);
        // dump(fsockopen("www.baidu.com", 80, $errno, $errstr, 30));
        // $wxApi = new WxAPI();
        // dump($wxApi->sendTemplateSync());
        // $start = time();
        // for ($i = 0; $i < 1000000000; $i++) { }
        // echo time() - $start;
        // echo date('Y-m-d H:i:s', Cfg::getCfg('active_date')[0]);
        // echo (int) !0;

        // echo json_encode(
        //     [
        //         [
        //             'fee' => 100,
        //             'count' => 3000
        //         ],
        //         [
        //             'fee' => 200,
        //             'count' => 6000
        //         ],
        //         [
        //             'fee' => 500,
        //             'count' => 15000
        //         ],
        //         [
        //             'fee' => 1000,
        //             'count' => 30000
        //         ]
        //     ],
        //     JSON_UNESCAPED_UNICODE
        // );

        // Cache::set('monthOptime', 0);
        // echo date('Ym');
        // echo ceil(-1);
        // dump(json_decode('{"a":1}', true));

        // test('hello');
        // $wxApi = new WxAPI();
        // $data = $wxApi->getwxacode('/pages/index/index?starid=' . 1);

        // file_put_contents(ROOT_PATH . 'public/uploads/1.jpg', $data);

        // echo date('Ym', time() - 3600);

        // dump(file_get_contents('https://ww2.sinaimg.cn/large/005BYqpggy1g2u2xti6raj305k05ka9v.jpg'));
        // dump((new WxMsg())->getMediaId(ROOT_PATH . 'public/uploads/cust-0.jpg'));
        // echo strtotime('06:00:00');
        // dump((new WxAPI('gzh'))->addMaterial(ROOT_PATH . 'public/uploads/test/4.png'));
        // echo urlencode('https://rank.xiaolishu.com/#/pages/signin/signin');

        // $wxApi = new WxAPI('wx7dc912994c80d9ac');
        // dump($wxApi->createMenu());

        // echo strtotime('-1 day');

        // echo (int)null;

        // try {
        //     //code...

        //    throw new Exception('cuowu');

        // } catch (\Throwable $th) {
        //     //throw $th;
        //     echo $th->getMessage();
        // }
        // $res = $this->test();

        // die((string)213213);
        // echo date('y-m-d');
        // $res = (new WxAPI('wx7dc912994c80d9ac'))->uploadMedia(ROOT_PATH . 'public' . DS . 'test.png');
        // dump($res);

        //    cache(' test ',1);
        //    cache(' test ',2);

        //    dump(cache(' test '));

        // Cache::set(' lockSend ', [
        //     ' isLock ' => 1,
        //     ' time ' => time()
        // ]);
        // die(Log::record(' hello ', ' error '));
        // echo json_encode([
        //     [
        //         ' title ' => ' ',
        //         "imageUrl"  => ' ',
        //     ],
        //     [
        //         ' title ' => ' ',
        //         "imageUrl"  => ' ',
        //     ],
        //     [
        //         ' title ' => ' ',
        //         "imageUrl"  => ' ',
        //     ],
        // ], true);
        // phpinfo();

        // ini_set(' memory_limit ', ' 128 M ');
        // $string = str_pad(' 1 ', 128 * 1024 * 1024);


        // include_once APP_PATH . ' wx / crypto / wxBizMsgCrypt . php ';
        // require_once APP_PATH . ' wx / aes / wxBizDataCrypt . php ';

        // dump(memory_get_usage());
        // Common::res([' data'=>1]);
    }
}
