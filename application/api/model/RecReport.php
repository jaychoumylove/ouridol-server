<?php
/**
 * Created by PhpStorm.
 * User: qucaixian
 * Date: 2020/6/12
 * Time: 14:03
 */

namespace app\api\model;


use app\base\model\Base;

class RecReport extends Base
{
    // 限制五分钟内十人举报自动永久禁言
    const LIMIT_REPORT = 5;
    const LIMIT_MINUTE = 5;

    /**
     * @param $userId
     * @param $reportId
     * @param $type
     * @param $data
     * @return RecReport
     */
    public static function addReport ($userId, $reportId, $type, $data)
    {
        return self::create([
            'user_id'   => $userId,
            'report_id' => $reportId,
            'type'      => $type,
            'extend'    => json_encode($data)
        ]);
    }

    /**
     * 检查是否允许永久禁言
     * @param $reportId
     * @return bool
     */
    public static function checkReport ($reportId)
    {
        $timeFormat = sprintf('+%smin', self::LIMIT_MINUTE);
        $time = strtotime($timeFormat);

        $info = self::where('report_id', $reportId)
            ->where('create_time', '>', $time)
            ->field('count(1) as _c')
            ->find();

        return $info->getData('_c') > self::LIMIT_REPORT;
    }
}