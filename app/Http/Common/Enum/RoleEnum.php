<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/20
 * Time: 5:48
 */

namespace App\Http\Common\Enum;


class RoleEnum extends BaseEnum
{
    /**
     * 角色类型
     */
    const IS_CATE_ZERO  = 0;   //角色
    const IS_CATE_ONE  = 1;   //分类

    /*
     * 角色状态
     * */
    const STATUS_ZERO = 0;
    const STATUS_ONE = 1;


    //获取comment
    public static $list = [
        'is_cate'=>[
            self::IS_CATE_ZERO => '角色',
            self::IS_CATE_ONE => '分类',
        ],
        'status'=>[
            self::STATUS_ZERO => '禁用',
            self::STATUS_ONE => '启用',
        ],
    ];

    //获取field style
    public static $listStyle = [
        'is_menu' => [
            self::IS_CATE_ZERO =>'layui-badge layui-bg-gray',
            self::IS_CATE_ONE =>'layui-badge layui-bg-blue',
        ]
    ];
}
