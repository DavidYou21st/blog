<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Common\Utils\Helper;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Blog\Comments;
use App\Services\Admin\Blog\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentsController extends BaseController
{
    /**
     * @var Comments
     */
    protected $comments;
    /**
     * @var serviceObject
     */
    protected $serviceObject;
    /**
     * CommentsController constructor.
     * @param Comments $comments
     */
    public function __construct(Comments $comments)
    {
        $this->comments = $comments;
        $this->serviceObject = CommentService::class;
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
        return view('admin.blog.comments.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function aproved()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reproved()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $id
     *
     * @return Response
     */
    public function aprove($id)
    {
        $comments = $this->comments->find($id);
        $comments->update(['status' => 1]);

        \Session::flash('success', trans('admin/blog.comments.aprove.messages.success'));

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $id
     *
     * @return Response
     */
    public function reprove($id)
    {
        $comments = $this->comments->find($id);
        $comments->update(['status' => 2]);

        \Session::flash('success', trans('admin/blog.comments.reprove.messages.success'));

        return redirect()->back();
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
