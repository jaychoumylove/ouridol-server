<?php
namespace app\api\model;

use app\base\model\Base;

class StarRank extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    public static function getRankList($page, $size, $rankField, $keywords, $sign)
    {
        if ($keywords !== '') {
            $ids = Star::where('name', 'like', '%' . $keywords . '%')->column('id');
            $w = ['star_id' => ['in', $ids]];
        } else {
            $w = '1=1';
        }

        $list = self::with('star')->where($w)->order($rankField . ' desc,id asc')
            ->page($page, $size)->select();
        $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);

        return $list;
    }
}
