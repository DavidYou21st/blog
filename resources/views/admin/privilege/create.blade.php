@extends('admin/public/admin_base')

@section('title','新增权限')

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
            <label class="layui-form-label required">权限名称</label>
            <div class="layui-input-block">
                <input type="text" name="privilege_name" lay-verify="required" lay-reqtext="权限名称不能为空" placeholder="请输入权限名称" value="" class="layui-input">
                <tip>请填写权限名称。</tip>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">类型</label>
            <div class="layui-input-block">
                <input type="radio" name="is_menu" value="0" title="方法" checked="checked">
                <input type="radio" name="is_menu" value="1" title="菜单">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">模块名称</label>
            <div class="layui-input-block">
                <input type="text" name="module_name" lay-verify="required" lay-reqtext="模块名称不能为空" placeholder="请输入模块名称" value="" class="layui-input">
                <tip>如果类型是菜单且无路由,请填写#</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">控制器名称</label>
            <div class="layui-input-block">
                <input type="text" name="controller_name" lay-verify="required" lay-reqtext="控制器名称不能为空" placeholder="请输入控制器名称" value="" class="layui-input">
                <tip>如果类型是菜单且无路由,请填写#</tip>
            </div>

        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">方法名称</label>
            <div class="layui-input-block">
                <input type="text" name="action_name" lay-verify="required" lay-reqtext="方法名称不能为空" placeholder="请输入方法名称" value="" class="layui-input">
                <tip>如果类型是菜单且无路由,请填写#</tip>
            </div>

        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">路由地址</label>
            <div class="layui-input-block">
                <input type="text" name="route_url" lay-verify="required" lay-reqtext="路由地址不能为空" placeholder="请输入路由地址" value="" class="layui-input">
                <tip>如果类型是菜单且无路由,请填写#</tip>
            </div>

        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">路由名称</label>
            <div class="layui-input-block">
                <input type="text" name="route_name" placeholder="请输入路由名称,没有留空" value="" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">参数</label>
            <div class="layui-input-block">
                <input type="text" name="parameter" placeholder="请输入参数名称" value="" class="layui-input">
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">图标</label>
            <div class="layui-input-block">
                <input type="text" name="privilege_icon" id="iconPicker" lay-filter="iconPicker" class="hide layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">target属性</label>
            <div class="layui-input-block">
                <input type="radio" name="target" value="_self" title="_self" checked="checked">
                <input type="radio" name="target" value="_blank" title="_blank">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">上级权限</label>
            <div class="layui-input-block">
                <div id="demo3" class="xm-select-demo"></div>
                <!--
                <input type="text" name="parent_id"  placeholder="请选择上级权限" value="" class="layui-input">
                -->
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
        layui.use(['form','iconPickerFa','layer','common','xmSelect'], function () {
            var form = layui.form,
                layer = layui.layer,
                $ = layui.$,
                iconPickerFa = layui.iconPickerFa,
                xmSelect = layui.xmSelect,
                C = layui.common;
                console.log( @json($privileges));



            var demo3 = xmSelect.render({
                el: '#demo3',
                model: { label: { type: 'text'},icon: 'hidden'},
                radio: true,
                name:'parent_id',
                clickClose: true,
                prop: {
                    name: 'privilege_name',
                    value: 'id'
                },
                tree: {
                    show: true,
                    strict: false,
                    indent: 15,
                    expandedKeys: false,
                    clickCheck: true,
                    clickExpand: false,
                },
                filterable: true,
                tips:'请选择上级权限',
                height: 'auto',
                data(){

                    return @json($privileges)
                }
            })


            //图标选择
            iconPickerFa.render({
                // 选择器，推荐使用input
                elem: '#iconPicker',
                // fa 图标接口
                url: "{{URL::asset('static/admin/lib/font-awesome-4.7.0/less/variables.less')}}",
                // 是否开启搜索：true/false，默认true
                search: true,
                // 是否开启分页：true/false，默认true
                page: true,
                // 每页显示数量，默认12
                limit: 16,
                // 点击回调
                click: function (data) {
                    console.log(data);
                },
                // 渲染成功后的回调
                success: function (d) {
                    console.log(d);
                }
            });

            //监听提交
            form.on('submit(saveBtn)', function (data) {
                var postData = data.field;
                /*
                var index = layer.alert(JSON.stringify(data.field), {
                    title: '最终的提交信息'
                }, function () {

                    // 关闭弹出层
                    layer.close(index);

                    var iframeIndex = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(iframeIndex);

                });
                */
                C.request.post({
                    url:"{{route('privilegeAdd')}}",
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

