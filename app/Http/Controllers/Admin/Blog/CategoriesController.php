<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Common\Utils\Helper;
use App\Http\Common\Utils\ReturnData;
use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\Blog\CategoryService;
use App\Services\Admin\Blog\PostsService;
use App\Services\Admin\ManagerService;
use Illuminate\Http\Request;
use App\Models\Admin\Blog\categories;
use Illuminate\Http\Response;

class CategoriesController extends BaseController
{
    /**
     * @var categories
     */
    protected $categories;

    /**
     * @var serviceObject
     */
    protected $serviceObject;
    /**
     * categoriesController constructor.
     * @param categories $categories
     */
    public function __construct(categories $categories)
    {
        $this->categories = $categories;
        $this->serviceObject = CategoryService::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $service = $this->getService($this->serviceObject);
        if(Helper::isAjaxRequest())
        {
            $search = $request->input();
            return $service->getList($search);
        }
        return view('admin.blog.categories.index');
    }

    /**
     * 显示表单
     */
    public function form()
    {
        return view('admin/blog/categories/form');
    }

    /*
     * 获取详细
     * */
    public function detail(Request $request){
        if(Helper::isAjaxRequest())
        {
            $id = $request->get('id');
            $service = $this->getService($this->serviceObject);
            $categoryModel =  $service->findModel($id);
            return ReturnData::success($categoryModel);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $service = $this->getService($this->serviceObject);
        $post = $request->post();
        return $service->add($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $service = $this->getService($this->serviceObject);
        $post = $request->post();
        return $service->edit($post,['id'=>$post['id']]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        if (Helper::isAjaxRequest())
        {
            $id = $request->post('id');
            return $this->getService($this->serviceObject)->del($id);
        } else {

        }
    }
}
