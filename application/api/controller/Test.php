<?php

namespace app\api\controller;

use think\Controller;
use app\base\service\Common;
use app\api\model\StarRank;
use app\api\model\Star;
use think\Db;

class Test extends Controller
{


    public function index()
    {
        echo json_encode([
            [
                'title' => '',
                "imageUrl"  => '',
            ],
            [
                'title' => '',
                "imageUrl"  => '',
            ],
            [
                'title' => '',
                "imageUrl"  => '',
            ],
        ], true);
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
