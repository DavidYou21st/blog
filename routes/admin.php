<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2024/3/12
 * Time: 9:53
 */
use Illuminate\Support\Facades\Route;


//Admin模块的路由组
Route::group(['namespace' => 'Admin','prefix'=>'admin','middleware' => ['admin.auth']],function() {
    //后台路由
    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/home','AdminController@home')->name('admin.home');
    Route::get('/theme','AdminController@theme')->name('admin.theme');
    Route::get('/updatePassword','AdminController@updatePassword')->name('admin.update.password');
    Route::get('/note','AdminController@note')->name('admin.update.note');
    Route::get('/lock-screen','AdminController@lockScreen')->name('admin.update.lockScreen');
    Route::get('/message','AdminController@message')->name('admin.update.message');

    //获取后台菜单
    Route::get('/getMenu', 'AdminController@getMenu')->name('getMenu');


    //登录退出相关路由
    Route::get('/login','LoginController@login')->name('adminLogin');
    Route::any('/verify', 'LoginController@verify')->name('loginVerify');
    Route::get('/login_out','LoginController@loginOut')->name('adminLoginOut');

    //基础配置
    Route::get('/system_config','AdminController@system')->name('systemConfig');
    Route::get('/spread','AdminController@spread')->name('systemSpread');

    //操作日志
    Route::get('/sys_operate_log','LogController@sysOperateLog')->name('sysOperateLog');
    Route::post('/del_operate_log','LogController@deleteSysOperateLog')->name('deleteSysOperateLog');
    //登录日志
    Route::get('/sys_login_log','LogController@sysLoginLog')->name('sysLoginLog');
    Route::post('/del_login_log','LogController@deleteSysLoginLog')->name('deleteSysLoginLog');
    /*
    Route::name('role.')->group(function () {
        Route::get('role_list/{name?}', 'mall\\RoleController@index')->name('users');
    });
    */

    //权限路由
    Route::any('/privilege_list', 'PrivilegeController@index')->name('privilegeList');
    Route::get('/privilege_add','PrivilegeController@create')->name('privilegeAdd');
    Route::post('/privilege_add','PrivilegeController@create')->name('privilegeAdd');
    Route::get('/privilege_edit','PrivilegeController@update')->name('privilegeEdit');
    Route::post('/privilege_edit','PrivilegeController@update')->name('privilegeEdit');
    Route::post('/privilege_del','PrivilegeController@delete')->name('privilegeDelete');


    //角色路由
    Route::any('/role_list','RoleController@index')->name('roleList');
    Route::get('/role_add','RoleController@create')->name('roleAdd');
    Route::post('/role_add','RoleController@create')->name('roleAdd');
    Route::get('/role_edit','RoleController@update')->name('roleEdit');
    Route::post('/role_edit','RoleController@update')->name('roleEdit');
    Route::post('/role_del','RoleController@delete')->name('roleDelete');
    Route::get('/role_authorize','RoleController@authorize')->name('roleAuthorize');
    Route::post('/role_authorize','RoleController@authorize');


    //管理员路由
    Route::any('/manager_list','ManagerController@index')->name('mangerList');
    Route::any('/manager_form','ManagerController@form')->name('mangerForm');
    Route::post('/manager_detail','ManagerController@detail')->name('mangerDetail');
    Route::get('/manager_add','ManagerController@create')->name('managerAdd');
    Route::post('/manager_add','ManagerController@create')->name('managerAdd');
    Route::get('/manager_edit','ManagerController@update')->name('managerEdit');
    Route::post('/manager_edit','ManagerController@update')->name('managerEdit');
    Route::post('/manager_del','ManagerController@delete')->name('managerDelete');
    Route::any('/manager_setting','ManagerController@manageSetting')->name('manageSetting');
    Route::get('/update_password','ManagerController@updatePassword')->name('updatePassword');
    Route::post('/update_password','ManagerController@updatePassword')->name('updatePassword');
    Route::post('/reset_password','ManagerController@resetPassword')->name('resetPassword');

    //部门路由
    Route::get('/branch_list','BranchController@index')->name('branchList');
    Route::post('/branch_list','BranchController@index');
    Route::any('/branch_add','BranchController@create')->name('branchAdd');
    Route::any('/branch_edit','BranchController@update')->name('branchEdit');
    Route::post('/branch_del','BranchController@delete')->name('branchDelete');
    Route::any('/branch_view','BranchController@view')->name('branchView');

    //博客路由
    Route::group(['prefix' => 'blog'], function () {
        // categories
        Route::get('categories','Blog\CategoriesController@index')->name('blogCategoriesList');
        Route::post('categories','Blog\CategoriesController@index');
        Route::any('categories/form','Blog\CategoriesController@form')->name('blogCategoriesForm');
        Route::any('categories/create','Blog\CategoriesController@create')->name('blogCategoriesAdd');
        Route::post('categories/update', 'Blog\CategoriesController@update')->name('blogCategoriesEdit');
        Route::post('categories/destroy', 'Blog\CategoriesController@destroy')->name('blogCategoriesDestroy');
        Route::post('categories/detail','Blog\CategoriesController@detail')->name('blogCategoriesDetail');

        // Posts
//        Route::resource('posts', 'Blog\PostsController');
        Route::get('posts','Blog\PostsController@index')->name('blogPostsList');
        Route::post('posts','Blog\PostsController@index');
        Route::any('posts/form','Blog\PostsController@form')->name('blogPostsForm');
        Route::any('posts/create','Blog\PostsController@create')->name('blogPostsAdd');
        Route::post('posts/update', 'Blog\PostsController@update')->name('blogPostsEdit');
        Route::post('posts/destroy', 'Blog\PostsController@destroy')->name('blogPostsDestroy');
        Route::any('posts/upload', 'Blog\PostsController@upload')->name('blogPostsUpload');
        Route::post('posts/detail','Blog\PostsController@detail')->name('blogPostsDetail');

        // Comments
        Route::group(['prefix' => 'comments'], function () {
            Route::get('list', 'Blog\CommentsController@index')->name('blogCommentsList');
            Route::post('list','Blog\CommentsController@index');
            Route::post('destroy', 'Blog\CommentsController@destroy')->name('blogCommentsDestroy');
        });
    });
    Route::any('/queue','\App\Http\Controllers\Admin\QueueController@setQueueTest')->name('setQueueTest');
});

