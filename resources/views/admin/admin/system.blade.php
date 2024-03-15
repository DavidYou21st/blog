@extends('admin.public.admin_base')

@section('title','系统配置')

@section('style')

    <style>
        .layui-form-item .layui-input-company {width: auto;padding-right: 10px;line-height: 38px;}
    </style>
@endsection

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <form id="userEditForm" lay-filter="userEditForm" class="layui-form model-form">

                <div class="layui-form-item">
                    <label class="layui-form-label required">系统名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="sitename" lay-verify="required" lay-reqtext="网站域名不能为空" placeholder="请输入网站名称"  value="layuimini" class="layui-input">
                        <tip>管理后台</tip>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label required">网站域名</label>
                    <div class="layui-input-block">
                        <input type="text" name="domain" lay-verify="required" lay-reqtext="网站域名不能为空" placeholder="请输入网站域名"  value="" class="layui-input">
                    </div>
                </div>

                <!--
                <div class="layui-form-item">
                    <label class="layui-form-label">缓存时间</label>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input type="text" name="cache" lay-verify="number" value="0" class="layui-input">
                    </div>
                    <div class="layui-input-inline layui-input-company">分钟</div>
                    <div class="layui-form-mid layui-word-aux">本地开发一般推荐设置为 0，线上环境建议设置为 10。</div>
                </div>
                -->

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label required">首页标题</label>
                    <div class="layui-input-block">
                        <textarea name="title" class="layui-textarea">管理后台</textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">META关键词</label>
                    <div class="layui-input-block">
                        <textarea name="keywords" class="layui-textarea" placeholder="多个关键词用英文状态 , 号分割"></textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">META描述</label>
                    <div class="layui-input-block">
                        <textarea name="descript" class="layui-textarea">管理平台</textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label required">版权信息</label>
                    <div class="layui-input-block">
                        <textarea name="copyright" class="layui-textarea">©  管理后台</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="setting">确认保存</button>
                    </div>
                </div>
                </form>
        </div>
    </div>
    </div>
@endsection


@section('js')
    <script>
        layui.use(['form'], function () {
            var form = layui.form
                , layer = layui.layer;

            //监听提交
            form.on('submit(setting)', function (data) {
                parent.layer.alert(JSON.stringify(data.field), {
                    title: '最终的提交信息'
                });
                return false;
            });

        });
    </script>

@endsection












