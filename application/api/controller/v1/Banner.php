<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Banner as BannerModel;
use app\base\service\Common;

class Banner extends Base
{

    /**banner图列表 */
    public function getList()
    {
        $banner_type = input('banner_type', 0);
        $datas = BannerModel::all(['type' => $banner_type]);

        Common::res(['data' => $datas]);
    }
}
