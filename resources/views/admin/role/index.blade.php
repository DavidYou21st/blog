@extends('admin.public.admin_base')

@section('title','菜单列表')

@section('style')
    <style>
        .layuimini-container .top_bar .layui-btn{
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <!-- 表格工具栏 -->
                <!--
                <form class="layui-form toolbar">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">权限名称:</label>
                            <div class="layui-input-inline">
                                <input name="authorityName" class="layui-input" placeholder="输入权限名称"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">菜单url:</label>
                            <div class="layui-input-inline">
                                <input name="menuUrl" class="layui-input" placeholder="输入路由地址"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">权限标识:</label>
                            <div class="layui-input-inline">
                                <input name="authority" class="layui-input" placeholder="输入权限标识"/>
                            </div>
                        </div>
                        <div class="layui-inline">&emsp;
                            <button class="layui-btn icon-btn" lay-filter="privilegeTbSearch" lay-submit>
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>&nbsp;
                        </div>
                    </div>
                </form>
                -->
                <!-- 数据表格 -->
                <table id="roleTable"></table>
            </div>
        </div>
    </div>
@endsection


@section('js')


    <!-- 表格操作列 -->
    <script type="text/html" id="RoleBar">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
        @verbatim
        {{#  if(d.is_cate == 0){ }}
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="auth">授权</a>
        {{#  } }}
        @endverbatim
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <!-- 表单弹窗 -->
    <script type="text/html" id="roleEditDialog">
        <form id="roleEditForm" lay-filter="roleEditForm" class="layui-form model-form"
              style="padding-right: 20px;">
            <input name="id" type="hidden"/>
            <div class="layui-row">
                <div class="layui-col-md12">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">角色名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="role_name" lay-verify="required" lay-reqtext="角色名称不能为空" placeholder="请输入角色名称" lay-verType="tips" value="" class="layui-input">
                            <tip>请输入角色名称</tip>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">状态</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="启用" checked="">
                            <input type="radio" name="status" value="0" title="禁用">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">是否属于分类</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_cate" value="1" title="分类" lay-verType="tips">
                            <input type="radio" name="is_cate" value="0" title="角色" checked="">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">上级角色</label>
                        <div class="layui-input-block">
                            <div id="roleEditParentSel" class="ew-xmselect-tree"></div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-block">
                            <input type="text" name="orders" placeholder="请输入排序数字" value="0" class="layui-input">
                            <tip>数字越小在最前面</tip>
                        </div>
                    </div>


                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">备注信息</label>
                        <div class="layui-input-block">
                            <textarea name="remark" class="layui-textarea" placeholder="请输入备注信息"></textarea>
                        </div>
                    </div>

                </div>

            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" lay-filter="roleEditSubmit" lay-submit>保存</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- js部分 -->

    <script type="text/javascript" src="{{asset(__ADMIN_MODULE__.'/iconPicker/iconPicker.js')}}"></script>

    <script>

        layui.use(['jquery','layer', 'form', 'admin', 'treeTable', 'util','xmSelect','zTree'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var admin = layui.admin;
            var treeTable = layui.treeTable;
            var util = layui.util;
            //var C = layui.common;
            var xmSelect = layui.xmSelect;
            var iconPicker = layui.iconPicker;
            var tbDataList = [];


            // 渲染表格
            var insTb = treeTable.render({
                elem: '#roleTable',
                url: "{{route('roleList')}}",
                toolbar: ['<p>',
                    '<button lay-event="add" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>&nbsp;',
                    '</p>'].join(''),
                tree: {
                    iconIndex: 1,
                    idName: 'id',
                    pidName: 'parent_id',
                    isPidData: true,
                },



                cols: [[
                    {type: 'numbers'},
                    {field: 'role_name', minWidth: 200, title: '角色名称'},
                    {field: 'status_name', title: '状态'},
                    {field: 'id', title: 'ID'},
                    {field: 'orders', width: 80, align: 'center', title: '排序号'},
                    {
                        width: 80, align: 'center',title: '类型',templet:function(d){
                            return d.is_cate_name;
                        }
                    },
                    {field: 'remark', align: 'center', title: '备注信息'},
                    {templet: '#RoleBar', width: 120, align: 'center', title: '操作'}
                ]],

                done: function (data) {
                    tbDataList = data;
                }
            });



            /* 表格操作列点击事件 */
            treeTable.on('tool(roleTable)', function (obj) {
                if (obj.event === 'edit') { // 修改
                    showEditModel(obj.data);
                } else if (obj.event === 'del') { // 删除
                    doDel(obj);
                } else if (obj.event === 'auth') {  // 权限管理
                    showPermModel(obj.data.id);
                }
            });

            /* 表格头工具栏点击事件 */
            treeTable.on('toolbar(roleTable)', function (obj) {
                if (obj.event === 'add') { // 添加
                    showEditModel();
                } else if (obj.event === 'del') { // 删除
                    var checkRows = insTb.checkStatus();
                    if (checkRows.length === 0) {
                        layer.msg('请选择要删除的数据', {icon: 2});
                        return;
                    }
                    var ids = checkRows.map(function (d) {
                        return d.id;
                    });
                    doDel({ids: ids});
                }
            });

            /* 表格搜索 */
            form.on('submit(privilegeTbSearch)', function (data) {
                doTbSearch(data.field, 'id');
                return false;
            });

            /* 添加 */
            $('#authoritiesAddBtn').click(function () {
                showEditModel();
            });

            /* 显示表单弹窗 */
            function showEditModel(mData) {
                admin.open({
                    type: 1,
                    area: '600px',
                    title: (mData ? '修改' : '添加') + '角色',
                    content: $('#roleEditDialog').html(),
                    success: function (layero, dIndex) {
                        // 回显表单数据
                        form.val('roleEditForm', mData);
                        // 表单提交事件
                        form.on('submit(roleEditSubmit)', function (data) {
                            //data.field.parentId = insXmSel.getValue('valueStr')

                            var loadIndex = layer.load(2);
                            if(mData){
                                data.field.role_id = data.field.id;
                                delete data.field.id;
                            }else{
                                delete data.field.id;
                            }

                            $.ajax({
                                url:mData ? '{{route('roleEdit')}}' : '{{route('roleAdd')}}',
                                data:data.field,
                                type:'post',
                                dataType:'json',
                                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                                success:function(res){
                                    layer.close(loadIndex);
                                    if (res.code === 0) {
                                        layer.close(dIndex);
                                        layer.msg(res.msg, {icon: 1});
                                        insTb.reload();
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                }
                            });

                            return false;
                        });
                        // 渲染下拉树
                        var insXmSel = xmSelect.render({
                            el: '#roleEditParentSel',
                            height: 'auto',
                            data: insTb.options.data,
                            initValue: mData ? [mData.parent_id] : [0],
                            model: {label: {type: 'text'}},
                            prop: {
                                name: 'role_name',
                                value: 'id'
                            },
                            name:'parent_id',
                            radio: true,
                            clickClose: true,

                            tree: {
                                show: true,
                                indent: 15,
                                strict: false,
                                expandedKeys: true,

                            }
                        });
                        // 弹窗不出现滚动条
                        //$(layero).children('.layui-layer-content').css('overflow', 'visible');
                    }
                });
            }

            /* 权限管理 */
            function showPermModel(roleId) {
                admin.open({
                    title: '角色权限分配',
                    btn: ['保存', '取消'],
                    content: '<ul id="roleAuthTree" class="ztree"></ul>',
                    success: function (layero, dIndex) {
                        var loadIndex = layer.load(2);
                        $.ajax({
                            url:'{{route('roleAuthorize')}}',
                            data:{role_id:roleId},
                            type:'get',
                            dataType:'json',
                            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                            success:function(res){
                                layer.close(loadIndex);
                                if (res.code === 0) {
                                    $.fn.zTree.init($('#roleAuthTree'), {
                                        check: {enable: true},
                                        data: {simpleData: {enable: true,idKey: "id",pIdKey: "parent_id"}},
                                        callback:{onNodeCreated:getNodes}
                                    }, res.data.privileges);

                                    function getNodes(event, treeId, treeNode){
                                        zTree = $.fn.zTree.getZTreeObj("roleAuthTree");
                                        zTree.expandNode(treeNode,true, true, true);
                                        if($.inArray(treeNode.id,res.data.role.rolePrivileges)>-1){
                                            zTree.checkNode(treeNode,true,false);
                                        }
                                    }
                                } else {
                                    layer.msg(res.msg, {icon: 2});
                                }
                            }
                        });

                        // 超出一定高度滚动
                        $(layero).children('.layui-layer-content').css({'max-height': '300px', 'overflow': 'auto'});
                    },
                    yes: function (dIndex) {
                        var insTree = $.fn.zTree.getZTreeObj('roleAuthTree');
                        var checkedRows = insTree.getCheckedNodes(true);
                        var ids = [];
                        for (var i = 0; i < checkedRows.length; i++) {
                            ids.push(checkedRows[i].id);
                        }
                        ids.sort();
                        if(ids.length < 1){
                            layer.msg('权限至少选择一项', {icon: 2});
                            return false;
                        }
                        var loadIndex = layer.load(2);

                        $.ajax({
                            url:'{{route('roleAuthorize')}}',
                            data:{role_id:roleId,privilege_ids:ids},
                            type:'post',
                            dataType:'json',
                            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                            success:function(res){
                                layer.close(loadIndex);
                                if (res.code === 0) {
                                    layer.msg(res.msg, {icon: 1});
                                } else {
                                    layer.msg(res.msg, {icon: 2});
                                }
                            }
                        });

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
                        url:'{{route('roleDelete')}}',
                        data:{role_id: obj.data ? obj.data.id : obj.ids},
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

            /* 搜索表格数据 */
            function doTbSearch(field, idName) {
                var ids = [], isClear = true;
                for (var i = 0; i < tbDataList.length; i++) {
                    var item = tbDataList[i], flag = true;
                    for (var f in field) {
                        if (!field.hasOwnProperty(f)) continue;
                        if (!field[f]) continue;
                        isClear = false;
                        if (!item[f] || item[f].indexOf(field[f]) === -1) {
                            flag = false;
                            break;
                        }
                    }
                    if (flag) ids.push(item[idName]);
                }
                if (isClear) {
                    insTb.clearFilter();
                } else {
                    insTb.filterData(ids);
                }
            }

        });
    </script>

@endsection

