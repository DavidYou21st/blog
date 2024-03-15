<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/20
 * Time: 3:37
 */

namespace App\Services\Admin;

interface ServiceInterface
{
    public function add($data);
    public function edit($data,$where);
    public function getList();
}
