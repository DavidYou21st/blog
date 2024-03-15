<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Common\Utils\Helper;
use App\Http\Common\Utils\ReturnData;
use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\Blog\CategoryService;
use App\Services\Admin\Blog\PostsService;
use Illuminate\Http\Request;
use App\Models\Admin\Blog\Posts;
use App\Models\Admin\Blog\Categories;
use Illuminate\Http\Response;

class PostsController extends BaseController
{
    /**
     * @var Posts
     */
    protected $posts;

    protected $categories;

    /**
     * PostsController constructor.
     * @param Posts $posts
     * @param Categories $categories
     */
    public function __construct(Posts $posts, Categories $categories)
    {
        $this->posts = $posts;
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $service = $this->getService(PostsService::class);
        if(Helper::isAjaxRequest())
        {
            $search = $request->input();
            return $service->getList($search);
        }
        return view('admin.blog.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $service = $this->getService(PostsService::class);
        $post = $request->post();
        return $service->add($post);
    }

    /**
     * 显示表单
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        $categories = $this->getService(CategoryService::class)->all();
        return view('admin/blog/posts/form',[
            'categories'=>$categories,
        ]);
    }

    /*
     * 获取详细
     * */
    public function detail(Request $request){
        if(Helper::isAjaxRequest())
        {
            $id = $request->get('id');
            $service = $this->getService(PostsService::class);
            $postsModel =  $service->findModel($id);
            return ReturnData::success($postsModel);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $service = $this->getService(PostsService::class);
        $post = $request->post();
        return $service->edit($post,['id'=>$post['id']]);
    }

    /**
     * Remove the specified resource from storage.
     *
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
