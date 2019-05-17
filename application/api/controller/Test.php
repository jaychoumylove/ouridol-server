<?php

namespace app\api\controller;

use think\Controller;
use app\base\service\Common;
use app\api\model\StarRank;
use app\api\model\Star;
use think\Db;
use GatewayClient\Gateway;

class Test extends Controller
{


    public function index()
    {
        // echo json_encode([
        //     [
        //         'title' => '',
        //         "imageUrl"  => '',
        //     ],
        //     [
        //         'title' => '',
        //         "imageUrl"  => '',
        //     ],
        //     [
        //         'title' => '',
        //         "imageUrl"  => '',
        //     ],
        // ], true);
        // phpinfo();

        // ini_set('memory_limit', '128M');
        // $string = str_pad('1', 128 * 1024 * 1024);


        Gateway::sendToGroup('star_' . 1, json_encode([
            'type' => 'chartMsg',
            'data' => 'hello'
        ], JSON_UNESCAPED_UNICODE));
        // dump(memory_get_usage());
        // Common::res(['data'=>1]);
    }
    // {
    //     // dump(Common::request('https://weibointl.api.weibo.cn/share/68524895.html?weibo_id=4366752424995193', false));
    //     $starid = Star::where('1=1')->column('id');

    //     foreach ($starid as $key => $value) {

    //         $in[] = [
    //             'id' => $value,
    //             'star_id' => $value,
    //             'week_hot' => 10000,
    //         ];
    //     }

    //     StarRank::insertAll($in);$in = [];



    //     // StarRank::where('1=1')->delete(true);



}
