<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/20
 * Time: 5:48
 */

namespace App\Http\Common\Enum;


class BranchEnum extends BaseEnum
{
    /**
     * 部门类型
     */
    const TYPE_ONE  = 1;   //公司
    const TYPE_TWO  = 2;   //部门

    //获取comment
    public static $list = [
        'type'=>[
            self::TYPE_ONE => '公司',
            self::TYPE_TWO => '部门',
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
