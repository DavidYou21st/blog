<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="{{asset(__ADMIN_IMAGES__.'/favicon.ico')}}" rel="icon">
    <title>管理平台</title>
    <link rel="stylesheet" href="{{asset(__ADMIN_LIBS__.'/layui/css/layui.css')}}"/>
    <link rel="stylesheet" href="{{asset(__ADMIN_MODULE__.'/admin.css?v=318')}}"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .full_bar{
            width:30px;
            height:30px;
            position: absolute;
            right:0;
            top:55%;
            z-index: 9999;
        }
        .full_bar i{
            font-size:28px;
        }
    </style>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="full_bar">
        <i class="layui-icon layui-icon-screen-full"></i>
    </div>

    <!-- 头部 -->
    <div class="layui-header">
        <div class="layui-logo">
            <!--
            <img src="{{asset(__ADMIN_IMAGES__.'/logo.png')}}"/>
            -->
            <cite>laravel8管理后台</cite>
        </div>

        <ul class="layui-nav layui-layout-left" id="topHeaderNav">
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
            </li>

        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="message" title="消息" data-url="/admin/message">
                    <i class="layui-icon layui-icon-notice"></i>
                    <span class="layui-badge-dot"></span>
                </a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="note" title="便签" data-url="/admin/note"><i class="layui-icon layui-icon-note"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="fullScreen" title="全屏"><i class="layui-icon layui-icon-   screen-full"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="lockScreen" title="锁屏" data-url="/admin/lock-screen"><i class="layui-icon layui-icon-password"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a>
                    <img src="{{asset(__ADMIN_IMAGES__.'/head.jpg')}}" class="layui-nav-img">
                    <cite id="full_name">{{$manager->full_name}}</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd lay-unselect><a ew-href="{{route('manageSetting')}}">个人中心</a></dd>
                    <dd lay-unselect><a ew-event="psw" data-url="/admin/update_password">修改密码</a></dd>
                    <hr>
                    <dd lay-unselect><a ew-event="logout" data-url="{{route('adminLoginOut')}}">退出</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="theme" title="主题" data-url="/admin/theme"><i class="layui-icon layui-icon-more-vertical"></i></a>
            </li>
        </ul>
    </div>

    <!-- 侧边栏 -->
    <div class="layui-side">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree" lay-filter="admin-side-nav" nav-id="xt1" style="margin: 15px 0;">
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-home"></i>&emsp;<cite>Dashboard</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/static/admin/page/console/workplace.html">工作台</a></dd>
                        <dd><a lay-href="/static/admin/page/console/console.html">控制台</a></dd>
                        <dd><a lay-href="/static/admin/page/console/dashboard.html">分析页</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-set"></i>&emsp;<cite>系统管理</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/static/admin/page/system/user.html">用户管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/role.html">角色管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/authorities.html">权限管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/dictionary.html">字典管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/organization.html">机构管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/login-record.html">登录日志</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-template"></i>&emsp;<cite>模板页面</cite></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a>表单页</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/template/form/form-basic.html">基础表单</a></dd>
                                <dd><a lay-href="/static/admin/page/template/form/form-advance.html">复杂表单</a></dd>
                                <dd><a lay-href="/static/admin/page/template/form/form-step.html">分步表单</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>列表页</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/template/table/table-basic.html">数据表格</a></dd>
                                <dd><a lay-href="/static/admin/page/template/table/table-advance.html">复杂表格</a></dd>
                                <dd><a lay-href="/static/admin/page/template/table/table-img.html">图片表格</a></dd>
                                <dd><a lay-href="/static/admin/page/template/table/table-card.html">卡片列表</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>错误页</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/template/error/error-500.html">500</a></dd>
                                <dd><a lay-href="/static/admin/page/template/error/error-404.html">404</a></dd>
                                <dd><a lay-href="/static/admin/page/template/error/error-403.html">403</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>登录页</a>
                            <dl class="layui-nav-child">
                                <dd><a href="page/template/login/login.html" target="_blank">登录页</a></dd>
                                <dd><a href="page/template/login/reg.html" target="_blank">注册页</a></dd>
                                <dd><a href="page/template/login/forget.html" target="_blank">忘记密码</a></dd>
                            </dl>
                        </dd>
                        <dd><a lay-href="/static/admin/page/template/user-info.html">个人中心</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-component"></i>&emsp;<cite>扩展组件</cite></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a>常用组件</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/plugin/basic/dialog.html">弹窗扩展</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/basic/dropdown.html">下拉菜单</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/basic/notice.html">消息通知</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/basic/tagsInput.html">标签输入</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/basic/cascader.html">级联选择</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/basic/steps.html">步骤条</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>进阶组件</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/plugin/advance/printer.html">打印插件</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/advance/split.html">分割面板</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/advance/formX.html">表单扩展</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/advance/tableX.html">表格扩展</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/advance/dataGrid.html">数据列表</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/advance/contextMenu.html">鼠标右键</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>其他组件</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/plugin/other/circleProgress.html">圆形进度条</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/other/editor.html">富文本编辑</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/other/mousewheel.html">鼠标滚轮</a></dd>
                                <dd><a lay-href="/static/admin/page/plugin/other/other.html">更多组件</a></dd>
                            </dl>
                        </dd>
                        <dd><a lay-href="/static/admin/page/plugin/more.html">更多扩展</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-app"></i>&emsp;<cite>经典实例</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/static/admin/page/example/dialog.html">弹窗实例</a></dd>
                        <dd><a lay-href="/static/admin/page/example/course.html">课程管理</a></dd>
                        <dd><a lay-href="/static/admin/page/example/calendar.html">排课管理</a></dd>
                        <dd><a lay-href="/static/admin/page/example/question.html">添加试题</a></dd>
                        <dd><a lay-href="/static/admin/page/example/file.html">文件管理</a></dd>
                        <dd><a lay-href="/static/admin/page/example/table-crud.html">表格CRUD</a></dd>
                        <dd><a href="page/example/side-more.html" target="_blank">多系统模式</a></dd>
                        <dd><a href="page/example/side-ajax.html" target="_blank">Ajax侧边栏</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-release"></i>&emsp;<cite>LayUI组件</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/static/admin/page/plugin/other/layui.html">组件演示</a></dd>
                        <dd><a lay-href="https://www.layui.com/doc/element/button.html#use">layui文档</a></dd>
                        <dd><a lay-href="https://layer.layui.com/">layer弹窗</a></dd>
                        <dd><a lay-href="https://www.layui.com/laydate/">laydate日期</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-unlink"></i>&emsp;<cite>多级菜单</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a>二级菜单</a></dd>
                        <dd>
                            <a>二级菜单</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="javascript:layer.msg('Hello!');">三级菜单</a></dd>
                                <dd>
                                    <a>三级菜单</a>
                                    <dl class="layui-nav-child">
                                        <dd><a lay-href="javascript:;">四级菜单</a></dd>
                                        <dd>
                                            <a>四级菜单</a>
                                            <dl class="layui-nav-child">
                                                <dd><a>五级菜单</a></dd>
                                                <dd>
                                                    <a lay-href="https://baidu.com">百度一下</a>
                                                </dd>
                                            </dl>
                                        </dd>
                                    </dl>
                                </dd>
                            </dl>
                        </dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a lay-href="//baidu.com"><i class="layui-icon layui-icon-unlink"></i>&emsp;<cite>一级菜单</cite></a>
                </li>
            </ul>
            <ul class="layui-nav layui-nav-tree" lay-filter="admin-side-nav" nav-id="xt2" style="margin: 15px 0;">

                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-set"></i>&emsp;<cite>系统管理</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/static/admin/page/system/user.html">用户管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/role.html">角色管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/authorities.html">权限管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/dictionary.html">字典管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/organization.html">机构管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/login-record.html">登录日志</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-template"></i>&emsp;<cite>模板页面</cite></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a>表单页</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/template/form/form-basic.html">基础表单</a></dd>
                                <dd><a lay-href="/static/admin/page/template/form/form-advance.html">复杂表单</a></dd>
                                <dd><a lay-href="/static/admin/page/template/form/form-step.html">分步表单</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>列表页</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/template/table/table-basic.html">数据表格</a></dd>
                                <dd><a lay-href="/static/admin/page/template/table/table-advance.html">复杂表格</a></dd>
                                <dd><a lay-href="/static/admin/page/template/table/table-img.html">图片表格</a></dd>
                                <dd><a lay-href="/static/admin/page/template/table/table-card.html">卡片列表</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>错误页</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="/static/admin/page/template/error/error-500.html">500</a></dd>
                                <dd><a lay-href="/static/admin/page/template/error/error-404.html">404</a></dd>
                                <dd><a lay-href="/static/admin/page/template/error/error-403.html">403</a></dd>
                            </dl>
                        </dd>
                        <dd>
                            <a>登录页</a>
                            <dl class="layui-nav-child">
                                <dd><a href="page/template/login/login.html" target="_blank">登录页</a></dd>
                                <dd><a href="page/template/login/reg.html" target="_blank">注册页</a></dd>
                                <dd><a href="page/template/login/forget.html" target="_blank">忘记密码</a></dd>
                            </dl>
                        </dd>
                        <dd><a lay-href="{{route('manageSetting')}}">个人中心</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-unlink"></i>&emsp;<cite>多级菜单</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a>二级菜单</a></dd>
                        <dd>
                            <a>二级菜单</a>
                            <dl class="layui-nav-child">
                                <dd><a lay-href="javascript:layer.msg('Hello!');">三级菜单</a></dd>
                                <dd>
                                    <a>三级菜单</a>
                                    <dl class="layui-nav-child">
                                        <dd><a lay-href="javascript:;">四级菜单</a></dd>
                                        <dd>
                                            <a>四级菜单</a>
                                            <dl class="layui-nav-child">
                                                <dd><a>五级菜单</a></dd>
                                                <dd>
                                                    <a lay-href="https://baidu.com">百度一下</a>
                                                </dd>
                                            </dl>
                                        </dd>
                                    </dl>
                                </dd>
                            </dl>
                        </dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a lay-href="//baidu.com"><i class="layui-icon layui-icon-unlink"></i>&emsp;<cite>一级菜单</cite></a>
                </li>
            </ul>
            <ul class="layui-nav layui-nav-tree" lay-filter="admin-side-nav" nav-id="xt3" style="margin: 15px 0;">
                <li class="layui-nav-item">
                    <a><i class="layui-icon layui-icon-set"></i>&emsp;<cite>系统管理</cite></a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/static/admin/page/system/user.html">用户管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/role.html">角色管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/authorities.html">权限管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/dictionary.html">字典管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/organization.html">机构管理</a></dd>
                        <dd><a lay-href="/static/admin/page/system/login-record.html">登录日志</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <!-- 主体部分 -->
    <div class="layui-body"></div>
    <!-- 底部 -->

    <div class="layui-footer layui-text">
        copyright © 2022 <a href="#" target="_blank">laravel8通用后台 </a> all rights reserved.
        <span class="pull-right">qq群：749972219</span>
    </div>


</div>

<!-- 加载动画 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>


@verbatim
    <!-- 侧边栏渲染模板 -->
    <script id="sideNav" type="text/html">
        {{#  layui.each(d, function(index, item){ }}
        {{# if(index == 0){ }}

        <ul class="layui-nav layui-nav-tree" lay-shrink="_all" lay-filter="admin-side-nav" nav-id="{{item.id}}" style="margin: 15px 0;">
            {{#  layui.each(item.child, function(index, menuItem){ }}
            <li class="layui-nav-item">
                <a lay-href="{{menuItem.href}}"><i class="layui-icon {{menuItem.icon}}"></i>&emsp;<cite>{{ menuItem.privilege_name }}</cite></a>
                {{# if(menuItem.child&&menuItem.child.length>0){ getSubMenus(menuItem.child); } }}
            </li>
            {{#  }); }}
        </ul>

        {{# } else { }}
        <ul class="layui-nav layui-nav-tree layui-hide" lay-shrink="_all" lay-filter="admin-side-nav" nav-id="{{item.id}}" style="margin: 15px 0;">
            {{#  layui.each(item.child, function(index, menuItem){ }}
            <li class="layui-nav-item">
                <a lay-href="{{menuItem.href}}"><i class="layui-icon {{menuItem.icon}}"></i>&emsp;<cite>{{ menuItem.privilege_name }}</cite></a>
                {{# if(menuItem.child&&menuItem.child.length>0){ getSubMenus(menuItem.child); } }}
            </li>
            {{#  }); }}
        </ul>
        {{# } }}
        {{#  }); }}

        {{# function getSubMenus(children){ }}
        <dl class="layui-nav-child">
            {{# layui.each(children, function(index, subItem){ }}
            <dd>
                <a lay-href="{{subItem.href}}">{{ subItem.privilege_name }}</a>
                {{# if(subItem.child&&subItem.child.length>0){ getSubMenus(subItem.child); } }}
            </dd>
            {{# }); }}
        </dl>
        {{# } }}
    </script>

<!-- 顶部系统栏渲染模板 -->
<script id="topNav" type="text/html">
    {{#  layui.each(d, function(index, item){ }}
    {{# if(item.id==1 || index==0){ }}
    <li class="layui-nav-item layui-hide-xs layui-this" lay-unselect><a nav-bind="{{item.id}}">{{ item.privilege_name }}</a></li>
    {{# } else { }}
    <li class="layui-nav-item layui-hide-xs" lay-unselect><a nav-bind="{{item.id}}"> {{item.privilege_name}} </a></li>
    {{# } }}
    {{#  }); }}
    <!-- 小屏幕下变为下拉形式 -->
    <li class="layui-nav-item layui-hide-sm layui-show-xs-inline-block" lay-unselect>
        <a>更多</a>
        <dl class="layui-nav-child" id="nav-sm">
            {{#  layui.each(d, function(index, item){ }}
            <dd lay-unselect><a nav-bind="{{item.id}}"> {{item.privilege_name}} </a></dd>
            {{#  }); }}
        </dl>
    </li>
</script>
@endverbatim

<!-- js部分 -->
<script type="text/javascript" src="{{asset(__ADMIN_LIBS__.'/layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset(__ADMIN_JS__.'/common.js?v=318')}}"></script>


<script>
    layui.use(['index','laytpl','admin','layer', 'element'], function () {
        var $ = layui.jquery;
        var index = layui.index;
        var admin = layui.admin;
        var laytpl = layui.laytpl;
        var element = layui.element;

        // ajax渲染侧边栏
        admin.req(getProjectUrl() + 'admin/getMenu', function(res){
            //将用户信息缓存到本地
            layui.data(admin.setter.tableName, {key: 'loginUser', value: res.data});

            //渲染顶部系统栏


            laytpl(topNav.innerHTML).render(res.data, function (html) {
                $('#topHeaderNav').append(html);
            });


            //渲染左侧菜单
            laytpl(sideNav.innerHTML).render(res.data, function (html) {
                $('.layui-side-scroll').html(html);
                element.render('nav');

                // 加载页面
                index.loadHome({
                    menuPath: "{{route('admin.home')}}",
                    menuName: '<i class="layui-icon layui-icon-home"></i>',
                    // 刷新后默认打开上次的页签
                    loadSetting: true,
                    // 并且只打开最后一个页签
                    onlyLast: true
                });
            });

        });

        /*
        // 默认加载主页
        index.loadHome({
            menuPath: '{{route('admin.home')}}',
            menuName: '<i class="layui-icon layui-icon-home"></i>'
        });
        */
        $('.full_bar').click(function () {

            if($('.layui-side').offset().top == '50'){
                //$('.layui-side').css('left','0');
                $('.layui-header').css('display','none');
                $('.layui-side').css({'top':'0','display':'none'});
                $('.layui-body').css({'top':'0','left':'0'});
                $('.full_bar i').removeClass('layui-icon-screen-full').addClass('layui-icon-screen-restore');
            }else{
                $('.layui-header').css('display','block');
                $('.layui-side').css({'top':'50px','display':'block'});
                $('.layui-body').css({'top':'50px','left':'235px'});
                $('.full_bar i').removeClass('layui-icon-screen-restore').addClass('layui-icon-screen-full');
            }
        });
    });
</script>
</body>
</html>
