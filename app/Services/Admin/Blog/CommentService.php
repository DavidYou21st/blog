<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 8:38
 */

namespace App\Services\Admin\Blog;

use App\Http\Common\Enum\BaseEnum;
use App\Services\Admin\BaseService;
use App\Services\Admin\ServiceInterface;
use App\Validate\Admin\Blog\CommentValidate;
use App\Models\Admin\Blog\Comments as CommentModel;
use App\Models\Admin\RolePrivilege;
use App\Http\Common\Utils\ReturnData;
use Symfony\Component\Routing\Exception\RouteNotFoundException;


class CommentService extends BaseService implements ServiceInterface
{

    /**
     * 获取模型
     * @return  CommentModel
     */
    public static function getModel()
    {
        return new CommentModel();
    }

    /**
     * @param $data array 要保存至数据库的数据
     *
     */
    public function add($data)
    {
        //验证器
        $validate = new CommentValidate();
        $validator = self::getValidate($data,'add',$validate);
        if ($validator->fails()){
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }
        try {
            self::getModel()->load($data)->save();

        } catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }

    //修改
    public function edit($data, $where = [])
    {
        if(empty($data)){return ReturnData::create(ReturnData::SUCCESS);}

        //验证器
        $validate = new CommentValidate();
        $validator = self::getValidate($data,'edit',$validate);
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}

        //查找当前模型
        $model = $this->getModel()->findModel($data['branch_id']);

        try {
            $model->load($data)->save();
        } catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }


    /**
     * 删除一条数据
     * @param $id
     * @param array $where
     * @return array
     */
    public function del($id,$where = [])
    {
        $model = $this->findModel($id,$where);
        try {
            $model->delete();
        } catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }


    /**
     * 得到模型
     * @param $id
     * @param array $where
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function findModel($id,$where = [])
    {
        if (empty($id) && empty($where)) {
            throw new RouteNotFoundException();
        }
        return self::getModel()->findModel($id,$where);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getList($data = [])
    {
        $model = self::getModel();
        $search = [];
        if (!empty($data) && isset($data['searchParams'])) {
            $search = json_decode($data['searchParams'], true);
        }
        $where = function ($query) use ($search) {
            if (isset($search['title'])) {
                $query->where('name', 'like', '%' . $search['title'] . '%');
            }
        };

        $count = $model->getCount();
        $list = [];
        if ($count > 0) {
            $list = $model->getAll($where)->each(function ($item) {
                $item['status_name'] = BaseEnum::getBoolValue($item['status']);
            });
        }
        return ReturnData::success($list, $count);
    }

    /*
     * 递归排序获取所有子栏目
     * $param array $arr
     * $param $parent_id int
     * $param $lev int
     * $param $isClear boolean
     * */
    public function _sort($arr,$parent_id=0,$lev=1,$isClear=true)
    {
        static $tree = [];
        if($isClear==true){
            $tree = [];
        }

        foreach($arr as $k=>$v) {
            if ($v['parent_id'] == $parent_id) {
                $v['name'] = $v['role_name'];
                $v['lev'] = $lev;
                $tree[] = $v;
                $this->_sort($arr,$v['id'],$lev+1,false);
            }
        }
        return $tree;
    }

    /**
     * 获取一个id下所有子类
     * @param int $parent_id
     * @return array
     */
    public function getSortList($parent_id = 0)
    {
        $list = self::getModel()->getAll()->toArray();
        return self::_sort($list,$parent_id);
    }

    /*
     *递归获取上下级关系
     *@param mix
     *@return array
    * */
    public function _sortChildren($arr,$parent_id,$select_id,$lev=0)
    {
        //取出所有的权限并递归排序
        $tree = [];
        foreach($arr as $k=>$v) {
            if ($v['parent_id'] == $parent_id) {
                $v['lev'] = $lev;
                //$v['selected'] = ($v['id'] == $select_id?true:false);
                $v['name'] = $v['branch_name'];
                $v['parentId'] = $v['parent_id'];
                $v['value'] = $v['id'];
                $a = $this->_sortChildren($arr,$v['id'],$select_id,$lev+1);
                if(!empty($a)){
                    $v['children'] = $a;
                }

                $tree[] = $v;
            }
        }
        return $tree;
    }


    /*
     *递归获取上下级关系
     *@param mix
     *@return array
    * */
    public function _sortChildren2($arr,$parent_id,$select_id,$lev=0)
    {
        //取出所有的权限并递归排序
        $tree = [];
        foreach($arr as $k=>$v) {
            if ($v['parent_id'] == $parent_id)
            {
                $v['lev'] = $lev;
                //$v['selected'] = ($v['id'] == $select_id?true:false);
                $v['name'] = $v['branch_name'];
                $v['parentId'] = $v['parent_id'];
                $v['value'] = $v['id'];
                $a = $this->_sortChildren2($arr,$v['id'],$select_id,$lev+1);
                if(!empty($a))
                {
                    $v['children'] = $a;
                }

                if($v['parent_id'] == 0)
                {
                    $tree['id'] = $v['id'];
                    $tree['name'] = $v['branch_name'];
                    $tree['parentId'] = $v['parent_id'];
                    $tree['children'] = $a;
                }
                else
                {
                    $tree[] = $v;
                }
            }
        }
        return $tree;
    }


    /**
     * 获取所有下级
     * @param int $parent_id
     * @param int $select_id
     * @return array
     */
    public function getSortChildren($parent_id=0,$select_id=0)
    {
        $list = self::getModel()->getAll()->toArray();
        //print_r($list);
        return $this->_sortChildren($list,$parent_id,$select_id);
    }


    /**
     * 获取所有下级,输出前端组织架构图结构
     * @param int $parent_id
     * @param int $select_id
     * @return array
     */
    public function getSortChildren2($parent_id=0,$select_id=0)
    {
        $list = self::getModel()->getAll()->toArray();
        //print_r($list);
        return $this->_sortChildren2($list,$parent_id,$select_id);
    }


    /**
     * 批量保存角色权限数据
     * @param $data
     * @return array
     */
    public function saveRolePrivilege($data){
        $tmp = [];
        if(!empty($data['privilege_ids']))
        {
            foreach($data['privilege_ids'] as $key => $value)
            {
                $tmp[] = [
                    'role_id'=>$data['role_id'],
                    'privilege_id'=>$value
                ];
            }

            $rolePrivilegeModel = new RolePrivilege();
            try
            {
                //先删除之前的
                $rolePrivilegeModel->delByWhere(['role_id'=>$data['role_id']]);
                $rolePrivilegeModel->add($tmp,1);
            }
            catch (\Exception $ex)
            {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
            }
            return ReturnData::create(ReturnData::SUCCESS);
        }
    }
}
