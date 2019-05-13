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

        if ($id) {
            $w = ['id' => $id];
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
}
