<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Article as ArticleModel;
use app\base\service\Common;

class Article extends Base
{

    public function getArticle()
    {
        $id = input('id');
        $id_str = input('id_str');
        if ($id) {
            $w = ['id' => $id];
        } else if ($id_str) {
            $w = ['id_str' => $id_str];
        } else {
            $w = '1=1';
        }
        $res = ArticleModel::where($w)->order('create_time desc,id desc')->find();
        Common::res(['data' => $res]);
    }

    public function getList()
    {
        $page = input('page', 1);
        $size = input('size', 10);

        $res = ArticleModel::where('1=1')->order('is_top desc,create_time desc')->page($page, $size)->select();
        Common::res(['data' => $res]);
    }

    public function formart()
    {
        $text = input('text');

        $result = [];
        $text = explode(';', $text);
        foreach ($text as $row) {
            if (strpos($row, '=') !== false) {
                $split = explode('=', $row);

                $left = trim($split[0]);
                $right = trim($split[1]);

                if ($left == '标题') $left = 'title';
                if ($left == '内容') $left = 'content';
                if ($left == '图片') $left = 'image';

                $result[] = [
                    'type' => $left,
                    'content' => $right,
                ];
            }
        }
        return view('formart', ['text' => json_encode($result, JSON_UNESCAPED_UNICODE)]);
    }
}
