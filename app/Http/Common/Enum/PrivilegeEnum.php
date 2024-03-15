<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/20
 * Time: 5:48
 */

namespace App\Http\Common\Enum;


class PrivilegeEnum extends BaseEnum
{
    /**
     * 权限类型
     */
    const IS_MENU_ZERO  = 0;   //方法
    const IS_MENU_ONE  = 1;   //菜单

    //获取comment
    public static $list = [
        'is_menu'=>[
            self::IS_MENU_ZERO => '方法',
            self::IS_MENU_ONE => '菜单',
        ],

    ];

    //获取field style
    public static $listStyle = [
        'is_menu' => [
            self::IS_MENU_ZERO =>'layui-badge layui-bg-gray',
            self::IS_MENU_ONE =>'layui-badge layui-bg-blue',
        ]
    ];
}
