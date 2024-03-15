<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/20
 * Time: 5:48
 */

namespace App\Http\Common\Enum;


class ManagerEnum extends BaseEnum
{
    /**
     * 账号状态
     */
    const STATUS_ZERO  = 0;   //禁用
    const STATUS_ONE  = 1;   //启用

    //获取status
    public static $list = [
        'status'=>[
            self::STATUS_ZERO => '禁用',
            self::STATUS_ONE => '启用',
        ],
    ];

    //获取field style
    public static $listStyle = [
        'status' => [
            self::STATUS_ZERO =>'layui-badge layui-bg-gray',
            self::STATUS_ONE =>'layui-badge layui-bg-blue',
        ]
    ];
}


