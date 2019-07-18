<?php

namespace app\base\model;

use think\Model;
use traits\model\SoftDelete;

class Base extends Model
{
    // 隐藏字段
    protected $hidden = ['update_time', 'delete_time'];

    protected function setCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    protected function setUpdateTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
    protected function setDeleteTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }


    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $update = ['delete_time'];

    /**数据库清理 */
    public static function clear()
    {
        $clearTime = time() - 3600 * 24 * 30;// 一个月前
        static::where('1=1')->whereTime('create_time', '<', $clearTime)->limit(10)->delete(true);
    }
}
