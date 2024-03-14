@extends('admin.public.admin_base')

@section('title','用户管理')

@section('content')
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <!-- 表格工具栏 -->
            <form class="layui-form toolbar">

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label">账&emsp;号:</label>
                        <div class="layui-input-inline">
                            <input name="account" class="layui-input" placeholder="输入账号"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">用户名:</label>
                        <div class="layui-input-inline">
                            <input name="full_name" class="layui-input" placeholder="输入用户名"/>
                        </div>
                    </div>

                    <div class="layui-inline">&emsp;
                        <button class="layui-btn icon-btn" lay-filter="userTbSearch" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                    </div>
                </div>

            </form>
            <!-- 数据表格 -->
            <table id="userTable" lay-filter="userTable"></table>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- 表格操作列 -->
<script type="text/html" id="userTbBar">
    @verbatim
    {{# if(d.id>1){ }}
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="reset">重置密码</a>
    {{# } }}
    @endverbatim
</script>
<!-- 表格状态列 -->
<script type="text/html" id="userTbState">
    @verbatim
    <input type="checkbox" lay-filter="userTbStateCk" value="{{d.userId}}" lay-skin="switch"
           lay-text="正常|锁定" {{d.status==0?'checked':''}} style="display: none;"/>
    <p style="display: none;">{{d.status==0?'正常':'锁定'}}</p>
    @endverbatim
</script>
<!-- 表单弹窗 -->
<script type="text/html" id="userEditDialog">

</script>

<!-- js部分 -->
<script>
    layui.use(['layer', 'form', 'table', 'util', 'admin', 'xmSelect'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var admin = layui.admin;
        var xmSelect = layui.xmSelect;

        /* 渲染表格 */
        var insTb = table.render({
            elem: '#userTable',
            url: '{{route('mangerList')}}',
            page: true,
            toolbar: ['<p>',
                '<button lay-event="add" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>&nbsp;',
                '</p>'].join(''),
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: 'ID', sort: true},
                {field: 'full_name',  title: '管理员名称'},
                {field: 'account',  title: '账号', sort: true},
                {field: 'email', title: 'email'},
                {field: 'tel', title: '手机号', sort: true},
                {field: 'status',  title: '状态', sort: true,hide:true},
                {title: '状态', sort: true,templet:function (d) {
                        return d.status_name;
                    }},
                {field: 'created_at',  title: '创建时间'},
                {title: '操作', toolbar: '#userTbBar', align: 'center', minWidth: 200}
            ]]
        });

        /* 表格搜索 */
        form.on('submit(userTbSearch)', function (data) {
            insTb.reload({where: {searchParams:JSON.stringify(data.field)}, page: {curr: 1}});
            return false;
        });

        /* 表格工具条点击事件 */
        table.on('tool(userTable)', function (obj) {
            if (obj.event === 'edit') { // 修改
                showEditModel(obj.data);
            } else if (obj.event === 'del') { // 删除
                doDel(obj);
            } else if (obj.event === 'reset') { // 重置密码
                resetPsw(obj);
            }
        });

        /* 表格头工具栏点击事件 */
        table.on('toolbar(userTable)', function (obj) {
            if (obj.event === 'add') { // 添加
                showEditModel();
            } else if (obj.event === 'del') { // 删除
                var checkRows = table.checkStatus('userTable');
                if (checkRows.data.length === 0) {
                    layer.msg('请选择要删除的数据', {icon: 2});
                    return;
                }
                var ids = checkRows.data.map(function (d) {
                    return d.userId;
                });
                doDel({ids: ids});
            }
        });

        /* 显示表单弹窗 */
        function showEditModel(mData) {

            var layIndex = admin.open({
                type: 1,
                title: (mData ? '修改' : '添加') + '用户',
                url: getProjectUrl() + 'admin/manager_form',
                area: ['1000px', '600px'],
                data: { data: mData },     // 传递数据到表单页面
                end: function () {
                    var layerData = admin.getLayerData(layIndex, 'formOk');
                    if (layerData) {  // 判断表单操作成功标识
                        insTb.reload();  // 成功刷新表格
                    }
                },
                success: function (layero, dIndex) {
                    form.render();
                    // 弹窗超出范围出现滚动条
                    $(layero).children('.layui-layer-content').css('overflow', 'scroll');
                    $(layero).find('[lay-submit]').focus();
                }
            });

        }



        /* 删除 */
        function doDel(obj) {
            layer.confirm('确定要删除选中数据吗？', {
                skin: 'layui-layer-admin',
                shade: .1
            }, function (i) {
                layer.close(i);
                var loadIndex = layer.load(2);

                $.ajax({
                    url:'{{route('managerDelete')}}',
                    data:{manager_id: obj.data ? obj.data.id : obj.ids},
                    type:'post',
                    dataType:'json',
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    success:function(res){
                        layer.close(loadIndex);
                        if (res.code === 0) {
                            layer.msg(res.msg, {icon: 1});
                            insTb.reload();
                        } else {
                            layer.msg(res.msg, {icon: 2});
                        }
                    }
                });

            });
        }

        /* 修改用户状态 */
        form.on('switch(userTbStateCk)', function (obj) {
            var loadIndex = layer.load(2);
            $.get('../../json/ok.json', {
                userId: obj.elem.value,
                state: obj.elem.checked ? 0 : 1
            }, function (res) {
                layer.close(loadIndex);
                if (res.code === 200) {
                    layer.msg(res.msg, {icon: 1});
                } else {
                    layer.msg(res.msg, {icon: 2});
                    $(obj.elem).prop('checked', !obj.elem.checked);
                    form.render('checkbox');
                }
            }, 'json');
        });

        /* 重置密码 */
        function resetPsw(obj) {
            layer.confirm('确定要重置“' + obj.data.full_name + '”的登录密码吗？', {
                skin: 'layui-layer-admin',
                shade: .1
            }, function (i) {
                layer.close(i);
                var loadIndex = layer.load(2);

                $.ajax({
                    url:'{{route('resetPassword')}}',
                    data:{manager_id:obj.data.id},
                    type:'post',
                    dataType:'json',
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    success:function(res){
                        layer.close(loadIndex);
                        if (res.code === 0) {
                            layer.msg(res.msg, {icon: 1});
                            insTb.reload();
                        } else {
                            layer.msg(res.msg, {icon: 2});
                        }
                    }
                });
            });
        }

    });
</script>
@endsection