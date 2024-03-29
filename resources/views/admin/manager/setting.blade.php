@extends('admin.public.admin_base')

@section('title','系统配置')

    @section('style')
    <style>
        /* 用户信息 */
        .user-info-head {
            width: 110px;
            height: 110px;
            line-height: 110px;
            position: relative;
            display: inline-block;
            border: 2px solid #eee;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            margin: 0 auto;
        }

        .user-info-head:hover:after {
            content: '\e681';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.3);
            font-size: 28px;
            padding-top: 2px;
            font-style: normal;
            font-family: layui-icon;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .user-info-head img {
            width: 110px;
            height: 110px;
        }

        .user-info-list-item {
            position: relative;
            padding-bottom: 8px;
        }

        .user-info-list-item > .layui-icon {
            position: absolute;
        }

        .user-info-list-item > p {
            padding-left: 30px;
        }

        .layui-line-dash {
            border-bottom: 1px dashed #ccc;
            margin: 15px 0;
        }

        /* 基本信息 */
        #userInfoForm .layui-form-item {
            margin-bottom: 25px;
        }

        /* 账号绑定 */
        .user-bd-list-item {
            padding: 14px 60px 14px 10px;
            border-bottom: 1px solid #e8e8e8;
            position: relative;
        }

        .user-bd-list-item .user-bd-list-lable {
            color: #333;
            margin-bottom: 4px;
        }

        .user-bd-list-item .user-bd-list-oper {
            position: absolute;
            top: 50%;
            right: 10px;
            margin-top: -8px;
            cursor: pointer;
        }

        .user-bd-list-item .user-bd-list-img {
            width: 48px;
            height: 48px;
            line-height: 48px;
            position: absolute;
            top: 50%;
            left: 10px;
            margin-top: -24px;
        }

        .user-bd-list-item .user-bd-list-img + .user-bd-list-content {
            margin-left: 68px;
        }
    </style>
    @endsection

@section('content')
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <!-- 左 -->
        <div class="layui-col-sm12 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-body" style="padding: 25px;">
                    <div class="text-center layui-text">
                        <div class="user-info-head" id="userInfoHead">
                            <img src="/static/admin/images/head.jpg" alt=""/>
                        </div>
                        <h2 style="padding-top: 20px;">Serati Ma</h2>
                        <p style="padding-top: 8px;">海纳百川，有容乃大</p>
                    </div>
                    <div class="layui-text" style="padding-top: 30px;">
                        <div class="user-info-list-item">
                            <i class="layui-icon layui-icon-username"></i>
                            <p>交互专家</p>
                        </div>
                        <div class="user-info-list-item">
                            <i class="layui-icon layui-icon-release"></i>
                            <p>某某公司－某某某事业群－某某平台部－某某技术部－UED</p>
                        </div>
                        <div class="user-info-list-item">
                            <i class="layui-icon layui-icon-location"></i>
                            <p>浙江省杭州市</p>
                        </div>
                    </div>
                    <div class="layui-line-dash"></div>
                    <h3>标签</h3>
                    <div class="layui-badge-list" style="padding-top: 6px;">
                        <span class="layui-badge layui-bg-gray">很有想法的</span>
                        <span class="layui-badge layui-bg-gray">专注设计</span>
                        <span class="layui-badge layui-bg-gray">辣~</span>
                        <span class="layui-badge layui-bg-gray">大长腿</span>
                        <span class="layui-badge layui-bg-gray">川妹子</span>
                        <span class="layui-badge layui-bg-gray">海纳百川</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- 右 -->
        <div class="layui-col-sm12 layui-col-md9">
            <div class="layui-card">
                <!-- 选项卡开始 -->
                <div class="layui-tab layui-tab-brief" lay-filter="userInfoTab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">基本信息</li>
                        <li>账号绑定</li>
                    </ul>
                    <div class="layui-tab-content">
                        <!-- tab1 -->
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" id="userInfoForm" lay-filter="userInfoForm"
                                  style="max-width: 400px;padding: 25px 10px 0 0;">

                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">管理账号</label>
                                    <div class="layui-input-block">
                                        <input type="text" lay-verify="required" lay-reqtext="管理账号不能为空" placeholder="请输入管理账号"  value="{{$manager->account}}" class="layui-input" disabled="disabled">
                                        <tip>填写自己管理账号的名称。</tip>
                                    </div>
                                </div>


                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">姓名</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="full_name" lay-verify="required" lay-reqtext="姓名不能为空" placeholder="请输入姓名"  value="{{$manager->full_name}}" class="layui-input">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">手机</label>
                                    <div class="layui-input-block">
                                        <input type="phone" name="tel" lay-verify="required" lay-reqtext="手机不能为空" placeholder="请输入手机"  value="{{$manager->tel}}" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">邮箱</label>
                                    <div class="layui-input-block">
                                        <input type="email" name="email"  placeholder="请输入邮箱"  value="{{$manager->email}}" class="layui-input">
                                    </div>
                                </div>


                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-filter="userInfoSubmit" lay-submit>更新基本信息
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- tab1 -->
                        <div class="layui-tab-item" style="padding-bottom: 20px;">
                            <div class="user-bd-list layui-text">
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-lable">密保手机</div>
                                    <div class="user-bd-list-text">已绑定手机：138****8293</div>
                                    <a class="user-bd-list-oper">修改</a>
                                </div>
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-lable">密保邮箱</div>
                                    <div class="user-bd-list-text">已绑定邮箱：easyweb@vip.com</div>
                                    <a class="user-bd-list-oper">修改</a>
                                </div>
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-img">
                                        <i class="layui-icon layui-icon-login-qq"
                                           style="color: #3492ED;font-size: 48px;"></i>
                                    </div>
                                    <div class="user-bd-list-content">
                                        <div class="user-bd-list-lable">绑定QQ</div>
                                        <div class="user-bd-list-text">当前未绑定QQ账号</div>
                                    </div>
                                    <a class="user-bd-list-oper">绑定</a>
                                </div>
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-img">
                                        <i class="layui-icon layui-icon-login-wechat"
                                           style="color: #4DAF29;font-size: 48px;"></i>
                                    </div>
                                    <div class="user-bd-list-content">
                                        <div class="user-bd-list-lable">绑定微信</div>
                                        <div class="user-bd-list-text">当前未绑定绑定微信账号</div>
                                    </div>
                                    <a class="user-bd-list-oper">绑定</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- //选项卡结束 -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- js部分 -->
<script>
    layui.use(['layer', 'form', 'element', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var element = layui.element;
        var admin = layui.admin;

        /* 选择头像 */
        $('#userInfoHead').click(function () {
            admin.cropImg({
                imgSrc: $('#userInfoHead>img').attr('src'),
                onCrop: function (res) {
                    $('#userInfoHead>img').attr('src', res);
                    parent.layui.jquery('.layui-layout-admin>.layui-header .layui-nav img.layui-nav-img').attr('src', res);
                }
            });
        });

        /* 监听表单提交 */
        form.on('submit(userInfoSubmit)', function (data) {
            //layer.msg(JSON.stringify(data.field));
            var postData = data.field;
            $.ajax({
                url:"{{route('manageSetting')}}",
                dataType:'json',
                type:'post',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                data:postData,
                success:function(data){
                    if(data.code==0){
                        $("#full_name",parent.document).text(postData.full_name);
                        layer.msg('修改成功', function () {

                        });
                        return false;
                    }else{
                        layer.msg(data.msg,{icon:2});
                        return false;
                    }
                },
                error:function(res){

                }
            });

            return false;
        });

    });
</script>
@endsection