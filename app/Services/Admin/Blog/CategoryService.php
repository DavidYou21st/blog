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
use App\Services\Admin\ManagerService;
use App\Validate\Admin\Blog\CategoryValidate;
use App\Models\Admin\RolePrivilege;
use App\Http\Common\Utils\ReturnData;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Services\Admin\ServiceInterface;
use App\Models\Admin\Blog\Categories as CategoryModel;

class CategoryService extends BaseService implements ServiceInterface
{

    /**
     * 获取模型
     * @return  CategoryModel
     */
    public static function getModel()
    {
        return new CategoryModel();
    }

    /**
     * @param $data array 要保存至数据库的数据
     *
     * @return array
     */
    public function add($data)
    {
        //验证器
        $validate = new CategoryValidate();
        $validator = self::getValidate($data, 'add', $validate);
        if ($validator->fails()) {
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
        if (empty($data)) {
            return ReturnData::create(ReturnData::SUCCESS);
        }

        //验证器
        $validate = new CategoryValidate();
        $validator = self::getValidate($data, 'edit', $validate);
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        //查找当前模型
        $model = $this->getModel()->findModel($data['id']);

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
    public function del($id, $where = [])
    {
        $model = $this->findModel($id, $where);
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
    public function findModel($id, $where = [])
    {
        if (empty($id) && empty($where)) {
            throw new RouteNotFoundException();
        }
        return self::getModel()->findModel($id, $where);
    }

    /**
     * @return array
     */
    public function all()
    {
        $model = self::getModel();
        return $model->getAll();
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
                $query->where('title', 'like', '%' . $search['title'] . '%');
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
}
