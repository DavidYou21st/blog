@extends('admin.public.admin_base')

@section('title','角色授权')

@section('style')
    <link rel="stylesheet" href="{{URL::asset('static/admin/css/public.css')}}" media="all">

    <style>
        body {
            background-color: #ffffff;
        }
        .layui-iconpicker-body.layui-iconpicker-body-page .hide {display: none;}
    </style>
@endsection

@section('content')
    <div class="layui-form layuimini-form">
        <div class="layui-form-item">
            <label class="layui-form-label required">角色名称</label>
            <div class="layui-input-block">
                <input type="text" name="role_name" lay-verify="required" lay-reqtext="角色名称不能为空" placeholder="请输入角色名称" value="{{$model->role_name}}" readonly class="layui-input">
                <tip>请输入角色名称</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">授权</label>
            <div class="layui-input-block">
                <div id="privilege_ids" class="ztree"></div>
            </div>
        </div>

        <input type="hidden" name="role_id" readonly value="{{$model->id}}">

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal layui-btn-sm" lay-submit lay-filter="saveBtn">确认保存</button>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script src="{{URL::asset('static/admin/js/lay-config.js?v=1.0.4')}}" charset="utf-8"></script>

    <script>
        layui.use(['form','layer','tree','common','xmSelect','zTree'], function () {
            var form = layui.form,
                layer = layui.layer,
                $ = layui.$,
                tree = layui.tree,
                //xmSelect = layui.xmSelect,
                C = layui.common,
                zNodes = @json($privileges);
            var zTree;
            var privilege = @json($model->privileges);

            var setting = {
                check:{
                    enable:true
                },
                data: {
                    simpleData: {
                        enable: true,
                        idKey: "id",
                        pIdKey: "parent_id",
                    }
                },
                callback:{
                    onNodeCreated:getNodes
                }
            };

            $.fn.zTree.init($("#privilege_ids"), setting, zNodes);

            function getNodes(event, treeId, treeNode){
                zTree = $.fn.zTree.getZTreeObj("privilege_ids");
                zTree.expandNode(treeNode,true, true, true);
                if($.inArray(treeNode.id,privilege)>-1){
                    zTree.checkNode(treeNode,true,false);
                }
            }

            //监听提交
            form.on('submit(saveBtn)', function (data) {
                var postData = data.field;
                var zTree = $.fn.zTree.getZTreeObj("privilege_ids");
                var nodes = zTree.getCheckedNodes(true);
                var arr = [];
                $.each(nodes, function (n, value) {
                    if(value.id > 0){
                        arr.push(value.id);
                    }
                });
                arr.sort();
                if(arr.length < 1){
                    C.msg.error('权限至少选择一项');
                    return false;
                }

                postData.privilege_ids = arr;
                //console.log(postData);
                //return false;
                C.request.post({
                    url:"{{route('roleAuthorize')}}",
                    data:postData,
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}
                },function (res) {
                    C.msg.success(res.msg, function () {
                        parent.location.reload();
                        var iframeIndex = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(iframeIndex);
                    });

                });

                return false;
            });

        });
    </script>
@endsection

