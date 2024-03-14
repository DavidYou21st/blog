@extends('admin/layouts.main')

@section('title','新增角色')

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
            <label class="layui-form-label required">部门名称</label>
            <div class="layui-input-block">
                <input type="text" name="branch_name" lay-verify="required" lay-reqtext="部门名称不能为空" placeholder="请输入部门名称" value="" class="layui-input">
                <tip>请输入部门名称</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-block">
                <select name="type" lay-filter="type">
                    <option value="">请选择类型</option>
                    <option value="1">公司</option>
                    <option value="2" selected="selected">部门</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">上级部门</label>
            <div class="layui-input-block">
                <div id="demo3" class="xm-select-demo"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="text" name="orders" placeholder="请输入排序数字" value="0" class="layui-input">
                <tip>数字越小在最前面</tip>
            </div>
        </div>


        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认保存</button>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="{{URL::asset('static/admin/js/lay-config.js?v=1.0.4')}}" charset="utf-8"></script>

    <script>
        layui.use(['form','common','layer','xmSelect'], function () {
            var form = layui.form,
                layer = layui.layer,
                C = layui.common,
                xmSelect = layui.xmSelect,
                $ = layui.$;

            var demo3 = xmSelect.render({
                el: '#demo3',
                model: { label: { type: 'text'},icon: 'hidden'},
                radio: true,
                name:'parent_id',
                clickClose: true,
                prop: {
                    name: 'branch_name',
                    value: 'id'
                },
                tree: {
                    show: true,
                    strict: false,
                    indent: 15,
                    expandedKeys: true,
                    clickCheck: true,
                    clickExpand: false,
                },

                filterable: true,
                tips:'请选择上级',
                height: 'auto',
                data(){
                    return @json($branch)
                }
            })


            //监听提交
            form.on('submit(saveBtn)', function (data) {

                var postData = data.field;

                C.request.post({
                    url:"{{route('branchAdd')}}",
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

