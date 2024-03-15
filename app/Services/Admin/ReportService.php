<?php 
namespace App\Services\Admin;

use App\Validate\Admin\ReportValidate;
use Modules\Common\Models\Report as ReportModel;
use App\Http\Common\Utils\ReturnData;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ReportService extends BaseService implements ServiceInterface
{
    /**
    * 获取模型
    * @return  ReportModel
    */
    public static function getModel()
    {
        return new ReportModel();
    }


    /**
    * @param $data array 要保存至数据库的数据
    *
    */
    public function add($data)
    {
        //验证器
        $validate = new ReportValidate();

        $validator = self::getValidate($data,'add',$validate);

        if ($validator->fails()){
            return ReturnData::create(ReturnData::FAIL, null, $validator->errors()->first());
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
        $validate = new ReportValidate();
        $validator = self::getValidate($data,'edit',$validate);
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
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
    * @return array
    */
    public function getList($data=[])
    {
        $model = self::getModel();
        $where = [];

        if(!empty($data) && isset($data['searchParams'])){
            $search = json_decode($data['searchParams'],true);
            $where = function($query) use ($search){

                if(isset($search['id'])) {
                    $query->where('id','=',$search['id']);
                }
    

                if(isset($search['type'])) {
                    $query->where('type', 'like', '%' . $search['type'] . '%');
                }
    

                if(isset($search['status'])) {
                    $query->where('status', 'like', '%' . $search['status'] . '%');
                }
    

                if(isset($search['menu_id'])) {
                    $query->where('menu_id', 'like', '%' . $search['menu_id'] . '%');
                }
    

                if(isset($search['create_user'])) {
                    $query->where('create_user', 'like', '%' . $search['create_user'] . '%');
                }
    

                if(isset($search['create_organize'])) {
                    $query->where('create_organize', 'like', '%' . $search['create_organize'] . '%');
                }
    

                if(isset($search['update_user'])) {
                    $query->where('update_user', 'like', '%' . $search['update_user'] . '%');
                }
    

                if(isset($search['field_462909'])) {
                    $query->where('field_462909', 'like', '%' . $search['field_462909'] . '%');
                }
    

                if(isset($search['field_988324'])) {
                    $query->where('field_988324', 'like', '%' . $search['field_988324'] . '%');
                }
    

                if(isset($search['field_948351'])) {
                    $query->where('field_948351', 'like', '%' . $search['field_948351'] . '%');
                }
    

                if(isset($search['field_833418'])) {
                    $query->where('field_833418', 'like', '%' . $search['field_833418'] . '%');
                }
    

                if(isset($search['field_711926'])) {
                    $query->where('field_711926', 'like', '%' . $search['field_711926'] . '%');
                }
    
            };
        }
        $count = $model->getCount($where);

        $list = [];
        if($count > 0){
            $list = $model->getAll($where);
        }
        return ReturnData::success($list,$count);
    }

}