<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 17:11
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    protected $guarded = []; //不可以注入的字段

    private static $errorMsg;

    const DEFAULT_ERROR_MSG = '操作失败!';

    /**
     * 设置错误信息
     * @param string $errorMsg
     * @return bool
     */
    protected static function setErrorInfo($errorMsg = self::DEFAULT_ERROR_MSG,$rollback = false)
    {
        if($rollback) self::rollbackTrans();
        self::$errorMsg = $errorMsg;
        return false;
    }

    /**
     * 获取错误信息
     * @param string $defaultMsg
     * @return string
     */
    public static function getErrorInfo($defaultMsg = self::DEFAULT_ERROR_MSG)
    {
        return !empty(self::$errorMsg) ? self::$errorMsg : $defaultMsg;
    }

    /**
     * 开启事务
     */
    public static function beginTrans()
    {
        DB::beginTransaction();
    }

    /**
     * 提交事务
     */
    public static function commitTrans()
    {
        DB::commit();
    }

    /**
     * 关闭事务
     */
    public static function rollbackTrans()
    {
        DB::rollBack();
    }

    /**
     * 根据结果提交滚回事务
     * @param $res
     */
    public static function checkTrans($res)
    {
        if($res){
            self::commitTrans();
        }else{
            self::rollbackTrans();
        }
    }

    //获取某一表的所有字段
    public static function getColumnListing($table)
    {
        return Schema::getColumnListing($table);
    }

    //过滤不是某一表的字段、过滤空值
    public static function filterTableColumn($data, $table)
    {
        $table_column = Schema::getColumnListing($table);

        if (!$table_column)
        {
            return $data;
        }

        foreach ($data as $k => $v)
        {
            if (!in_array($k, $table_column))
            {
                unset($data[$k]);
            }
            else
            {
                if($data[$k]==''){unset($data[$k]);} //过滤空值
            }
        }

        return $data;
    }

    //获取排序参数
    public static function getOrderByData($model, $orderby)
    {
        if ($orderby == 'rand()')
        {
            $model = $model->orderBy(\DB::raw('rand()'));
        }
        else
        {
            if (count($orderby) == count($orderby, 1))
            {
                $model = $model->orderBy($orderby[0], $orderby[1]);
            }
            else
            {
                foreach ($orderby as $row)
                {
                    $model = $model->orderBy($row[0], $row[1]);
                }
            }
        }

        return $model;
    }

}
