<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 8:38
 */

namespace App\Services\Admin\Blog;

use App\Services\Admin\BaseService;
use App\Services\Admin\ManagerService;
use App\Services\Admin\ServiceInterface;
use App\Validate\Admin\Blog\PostsValidate;
use App\Models\Admin\Blog\Posts as PostsModel;
use App\Http\Common\Enum\BaseEnum;
use App\Http\Common\Utils\ReturnData;
use Illuminate\Support\Carbon;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use function Ramsey\Uuid\Generator\timestamp;

class PostsService extends BaseService implements ServiceInterface
{

    /**
     * 获取模型
     * @return PostsModel
     */
    public static function getModel()
    {
        return new PostsModel();
    }

    /**
     * 新增数据库的方法
     * @param $data array 要保存至数据库的数据
     *
     */
    public function add($data)
    {
        //验证器
        $validate = new PostsValidate();
        $validator = self::getValidate($data, 'add', $validate);
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::FAIL, null, $validator->errors()->first());
        }
        //保存数据
        $model = self::getModel();
        try {
            if (!isset($data['publish_at'])) $data['publish_at'] = time();
            $data['publish_at'] = new Carbon($data['publish_at']);
            $data['status'] = isset($data['status']) ? 1 : 0;
            PostsModel::beginTrans();
            $model->load($data)->save();
            PostsModel::commitTrans();
        } catch (\Exception $ex) {
            PostsModel::rollbackTrans();
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }

    /**
     * 修改方法
     * @param $data array 要修改的数组
     * @param array $where 修改条件
     * @return array
     */
    public function edit($data, $where = [])
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::SUCCESS);
        }
        //验证器
        $validate = new PostsValidate();
        $validator = self::getValidate($data, 'edit', $validate);
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $model = $this->getModel()->findModel($data['id']);
        try {
            //开启事务
            PostsModel::beginTrans();
            $model->load($data)->save();
            PostsModel::commitTrans();
        } catch (\Exception $ex) {
            PostsModel::rollbackTrans();
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
     * 获取列表的方法
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
            $managerService = new ManagerService();
            $manager = $managerService->getLoginManager();
            if ($manager->id > ADMIN_MANAGER_ID) {
                $query->where('user_id', '=', $manager->id);
            }
        };


        $count = $model->getCount($where);
        $list = [];
        if ($count > 0) {
            $list = $model->getAll($where)->each(function ($item) {
                $item['status_name'] = BaseEnum::getBoolValue($item['status']);
            });
        }
        return ReturnData::success($list, $count);
    }
}

