<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/29
 * Time: 9:12
 */

namespace App\Services\Admin;


use App\Models\Admin\Config as ConfigModel;

class SystemService
{

    /**
     * 获取模型
     * @return  ConfigModel
     */
    public static function getModel()
    {
        return new ConfigModel();
    }

    /**
     * 存储获取系统配置
     * @param $config_key
     * @param array $data
     * @return array
     */
    public static function getSystemConfig($config_key,$data = []){
       $param = explode('.', $config_key);
       $model = self::getModel();
       //print_r($data);die();
       if(empty($data)){
           //如$config_key=shop_info则获取网站信息数组
           //如$config_key=shop_info.logo则获取网站logo字符串
           //$config = F($param[0],'');//直接获取缓存文件
           if(empty($config)){
               //缓存文件不存在就读取数据库
               //$res = \think\facade\Db::name('config')->where("inc_type",$param[0])->select();
               $res = $model->getAll(['inc_type'=>$param[0]]);
               if($res){
                   foreach($res as $k=>$val){
                       $config[$val['name']] = $val['value'];
                   }
                   //F($param[0],$config);
               }
           }
           if(count($param)>1){
               return $config[$param[1]];
           }else{
               return $config;
           }
       }else{
           //更新缓存
           //$result =  \think\facade\Db::name('config')->where("inc_type", $param[0])->select();
           $result =  $model->getAll(['inc_type'=>$param[0]]);
           if($result){
               foreach($result as $val){
                   $temp[$val['name']] = $val['value'];
               }
               foreach ($data as $k=>$v){
                   $newArr = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
                   if(!isset($temp[$k])){
                       //\think\facade\Db::name('config')->insert($newArr);//新key数据插入数据库
                       $model->add($newArr);
                   }else{
                       if($v!=$temp[$k]) {
                           //\think\facade\Db::name('config')->where("name", $k)->save($newArr);//缓存key存在且值有变更新此项
                           $model->edit($newArr,['name'=>$k]);
                       }
                   }
               }
               //更新后的数据库记录
               //$newRes = \think\facade\Db::name('config')->where("inc_type", $param[0])->select();
               $newRes = $model->getAll(['inc_type'=>$param[0]]);
               foreach ($newRes as $rs){
                   $newData[$rs['name']] = $rs['value'];
               }
           }else{
               foreach($data as $k=>$v){
                   $newArr[] = ['name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]];
               }
               //\think\facade\Db::name('config')->insertAll($newArr);
               $model->add($newArr);
               $newData = $data;
           }
           return $newData;
           //return F($param[0],$newData);
       }
   }
}
