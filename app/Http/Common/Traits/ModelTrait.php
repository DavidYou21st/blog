<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 13:18
 */

namespace App\Http\Common\Traits;

use Illuminate\Support\Facades\DB;
use SebastianBergmann\Comparator\ObjectComparator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

trait ModelTrait
{

    //获取DB
    public function getDb()
    {
        //return DB::table($this->table);
        return new static;
    }

    /**
     * 得到模型
     *
     * @param int $id
     * @param array $where
     *
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function findModel($id,$where = [])
    {
        if (!empty($where)) {

            return self::where($where)->first();
        }else{
            return self::find($id);
        }
    }

    /**
     * 根据主键删除一条数据
     * @param $id
     * @return bool $type 返回成功失败
     */
    public function destroyModel($id)
    {
        return false !== self::destroy($id);
    }


    /**
     * 根据where条件删除一条数据
     * @param $id
     * @return bool $type 返回成功失败
     */
    public function delByWhere($where=[],$field='')
    {
        if(empty($where)){
            return false;
        }

        if(is_array($where) && $field){
            return false !== self::whereIn($field,$where)->delete();
        }
        return false !== self::where($where)->delete();
    }


    /**
     * 获取总记录数
     * @param array $where
     * @return mixed
     */
    public function getCount($where = []){
        $model = $this->getDb();
        if($where){$model = $model->where($where);}
        $count = $model->count();
        return $count;
    }

    /**
     * 列表
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $offset 偏移量
     * @param int $limit 取多少条
     * @return array
     */
    public function getReturnList($where = [], $order = '', $field = '*', $offset = 0, $limit = 10)
    {
        $model = $this->getDb();

        //$model = self::where($where);
        if($where){$model = $model->where($where);}

        $res['count'] = $model->count();

        $res['data'] = [];

        if($res['count'] > 0)
        {
            if($field){if(is_array($field)){$model = $model->select($field);}else{$model = $model->select(DB::raw($field));}}
            if($order){$model = parent::getOrderByData($model, $order);}
            if($offset){}else{$offset = 0;}
            if($limit){}else{$limit = 10;}

            $res['data'] = $model->skip($offset)->take($limit)->get();
        }

        return $res;
    }

    /**
     * 分页，用于前端html输出
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $limit 每页几条
     * @param int $page 当前第几页
     * @return array
     */
    public function getPaginate($where = [], $order = '', $field = '*', $limit = 10)
    {
        $res = $this->getDb();
        if($where){$res = $res->where($where);}
        if($field){if(is_array($field)){$res = $res->select($field);}else{$res = $res->select(DB::raw($field));}}
        if($order){$res = parent::getOrderByData($res, $order);}
        if($limit){}else{$limit = 10;}

        return $res->paginate($limit);
    }


    /**
     * 获取一条数据带有关联查询
     * @param $where
     * @param array $with
     * @return mixed
     */
    public function findOne($where,$with=[])
    {
        if(!empty($with)){
            return self::where($where)->with($with)->first();
        }else{
            return self::where($where)->first();
        }
    }

    /**
     * 获取多条数据带有关联查询
     * @param $where
     * @param array $with
     * @return mixed
     */
    public function findAll($where,$with=[]){
        if(!empty($with)){
            return self::where($where)->with($with)->get();
        }else{
            return self::where($where)->get();
        }
    }


    /**
     * 获取查询单条数据
     *
     * @param array $condition
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getQueryOne($condition = [])
    {
        $model = $this->getDb();
        $where = empty($condition['where']) ? [] : $condition['where'];
        $query = $model->where($where);
        if (! empty($condition['field'])) {
            if(is_array($condition['field'])){
                $query = $query->select($condition['field']);
            }else{
                $query = $query->select(DB::raw($condition['field']));
            }
        }
        if (! empty($condition['order'])) {
            $query = parent::getOrderByData($query, $condition['order']);
        }

        if (! empty($condition['with'])) {
            $query->with($condition['with']);
        }

        if($condition['offset']){
            $query = $query->skip($condition['offset']);
        }
        if($condition['limit']){
            $query = $query->take($condition['limit']);
        }
        return $query->first();
    }

    /**
     * 获取查询数据
     *
     * @param array $condition
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getQueryAll($condition = [])
    {
        $model = $this->getDb();
        $where = empty($condition['where']) ? [] : $condition['where'];
        $query = $model->where($where);
        if (! empty($condition['field'])) {
            if(is_array($condition['field'])){
                $query = $query->select($condition['field']);
            }else{
                $query = $query->select(DB::raw($condition['field']));
            }
        }
        if (! empty($condition['order'])) {
            $query = parent::getOrderByData($query, $condition['order']);
        }

        if (! empty($condition['with'])) {
            $query->with($condition['with']);
        }

        if($condition['offset']){
            $query = $query->skip($condition['offset']);
        }
        if($condition['limit']){
            $query = $query->take($condition['limit']);
        }
        return $query->get();
    }


    /**
     * 查询全部
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $limit 取多少条
     * @return array
     */
    public function getAll($where = [], $order = '', $field = '*', $limit = '', $offset = '')
    {
        $res = $this->getDb();
        if($where){$res = $res->where($where);}
        if($field){if(is_array($field)){$res = $res->select($field);}else{$res = $res->select(DB::raw($field));}}
        if($order){$res = parent::getOrderByData($res, $order);}
        if($offset){$res = $res->skip($offset);}
        if($limit){$res = $res->take($limit);}

        $res = $res->get();

        return $res;
    }

    /**
     * 获取一条
     * @param array $where 条件
     * @param string $field 字段
     * @return array
     */
    public function getOne($where, $field = '*')
    {
        $res = $this->getDb();

        if($where){$res = $res->where($where);}
        if($field){if(is_array($field)){$res = $res->select($field);}else{$res = $res->select(DB::raw($field));}}

        $res = $res->first();

        return $res;
    }

    /**
     * 不自动维护created_at,updated_at字段
     * 添加
     * @param array $data 数据
     * @return int
     */
    public function add(array $data,$type = 0)
    {
        if($type==0)
        {
            // 新增单条数据并返回主键值
            return self::insertGetId(parent::filterTableColumn($data,$this->table));

        }
        elseif($type==1)
        {
            /**
             * 添加单条数据
             * $data = ['foo' => 'bar', 'bar' => 'foo'];
             * 添加多条数据
             * $data = [
             *     ['foo' => 'bar', 'bar' => 'foo'],
             *     ['foo' => 'bar1', 'bar' => 'foo1'],
             *     ['foo' => 'bar2', 'bar' => 'foo2']
             * ];
             */
            //return DB::table($this->table)->insert($data);
            return self::insert($data);
       }
    }

    /**
     * 自动维护created_at,updated_at字段
     * @param $data
     */
    public function load($data){

        $insertData = parent::filterTableColumn($data,$this->table);
        if(!empty($insertData)){

            /*
            foreach($insertData as $key=>$val){
                $this->$key = $val;
                $this->setAttribute($key,$val);
            }
            */
            $this->setRawAttributes($insertData);
        }
        return $this;
    }

    /*
     * 过滤不再数据表中的字段与空值字段
     * */
    public function allowedField($data){
        return parent::filterTableColumn($data,$this->table);
    }

    /**
     * 修改
     * @param array $data 数据
     * @param array $where 条件
     * @return int
     */
    public function edit($data, $where = [])
    {
        $res = $this->getDb();
        return $res->where($where)->update(parent::filterTableColumn($data, $this->table));
    }

    /**
     * 删除
     * @param array $where 条件
     * @return bool
     */
    public function del($where)
    {
        $res = $this->getDb();
        $res = $res->where($where)->delete();

        return $res;
    }

    /**
     * 打印sql
     */
    public function toSql($where)
    {
        return $this->getDb()->where($where)->toSql();
    }
}
