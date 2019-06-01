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

        // 韩星榜
        if ($sign !== 0) {
            $return = [];
            foreach ($list as $value) {
                if ($value['star']['sign'] == $sign) {
                    $return[] = $value;
                }
            }
            return $return;
        } else {
            return $list;
        }
    }
}
