<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/20
 * Time: 5:48
 */

namespace App\Http\Common\Enum;
/**
 * Class BaseEnum 基础枚举类
 *
 * @package seller\common\enum
 */
abstract class BaseEnum
{
    const BOOL_YES = 1;
    const BOOL_NO  = 0;
    public static $list        = [];
    public static $listStyle   = [];
    public static $defaultName = "未设置";

    /**
     * 获取枚举描述
     *
     * @param string $attrName
     * @param string $value
     *
     * @return string
     */
    public static function getName($attrName, $value,$options = null)
    {
        if (! empty(static::$list[ $attrName ])) {
            if (isset(static::$list[ $attrName ][ $value ])) {
                if ($options !== null) {
                    if (! empty(static::$listStyle[ $attrName ]) && ! empty(static::$listStyle[ $attrName ][ $value ])) {
                        return '<span class="'.static::$listStyle[$attrName][$value].'">'.static::$list[$attrName][$value].'</span>';

                    }
                    return '<span class="label label-default">'.static::$list[$attrName][$value].'</span>';
                }else {
                    return static::$list[$attrName][$value];
                }
            } else {
                if ($options !== null) {
                    return '<span class="label label-default">'.static::$defaultName.'</span>';
                }else {
                    return static::$defaultName;
                }
            }
        }

        return "未知属性";
    }

    /**
     * 获取所有枚举
     *
     * @param string $attrName
     *
     * @return array
     */
    public static function getAll($attrName)
    {
        if (! empty(static::$list[ $attrName ])) {
            return static::$list[ $attrName ];
        }

        return [];
    }

    /**
     * 获取label 数据
     *
     * @param $attrName
     *
     * @return string
     */
    public static function getAllToJson($attrName)
    {
        $data = [];
        if (! empty(static::$list[ $attrName ])) {
            foreach (static::$list[ $attrName ] as $value => $label) {
                $data[] = [
                    'value' => $value,
                    'label' => $label,
                ];
            }
        }

        return json_encode($data);
    }


    /**
     * 获取所有枚举的key集合
     *
     * @param string $attrName
     *
     * @return array
     */
    public static function getKeys($attrName)
    {
        if (! empty(static::$list[ $attrName ])) {
            return array_keys(static::$list[ $attrName ]);
        }

        return [];
    }

    /*
     * 获取bool值图标
     * */
    public static function getBoolValue($value)
    {
        if($value == self::BOOL_YES){
            return '<i class="layui-icon layui-icon-ok"></i>';
        }else if($value == self::BOOL_NO){
            return '<i class="layui-icon layui-icon-close"></i>';
        }else{
            return '';
        }
    }

}
