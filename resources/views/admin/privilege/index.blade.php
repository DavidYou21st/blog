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
                <table id="privilegeTable"></table>
            </div>
        </div>
    </div>
@endsection


@section('js')


    <!-- 表格操作列 -->
    <script type="text/html" id="privilegeBar">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <!-- 表单弹窗 -->
    <script type="text/html" id="branchEditDialog">
        <form id="privilegeEditForm" lay-filter="privilegeEditForm" class="layui-form model-form"
              style="padding-right: 20px;">
            <input name="id" type="hidden"/>
            <div class="layui-row">
                <div class="layui-col-md6">

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">权限名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="privilege_name" lay-verify="required" lay-reqtext="权限名称不能为空" placeholder="请输入权限名称" value=""  lay-verType="tips" class="layui-input">
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
                        <label class="layui-form-label layui-form-required">模块名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="module_name" lay-verify="required" lay-reqtext="模块名称不能为空" placeholder="请输入模块名称" lay-verType="tips" value=""  class="layui-input">
                            <tip>如果类型是菜单且无路由,请填写#</tip>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">控制器名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="controller_name" lay-verify="required" lay-reqtext="控制器名称不能为空" placeholder="请输入控制器名称" lay-verType="tips" value="" class="layui-input">
                            <tip>如果类型是菜单且无路由,请填写#</tip>
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">方法名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="action_name" lay-verify="required" lay-reqtext="方法名称不能为空" placeholder="请输入方法名称" lay-verType="tips" value="" class="layui-input">
                            <tip>如果类型是菜单且无路由,请填写#</tip>
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">路由地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="route_url" lay-verify="required" lay-reqtext="路由地址不能为空" placeholder="请输入路由地址"  lay-verType="tips" value="" class="layui-input">
                            <tip>如果类型是菜单且无路由,请填写#</tip>
                        </div>

                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">路由名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="route_name" placeholder="请输入路由名称,没有留空" value="" class="layui-input">
                        </div>
                    </div>
                </div>

                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">参数</label>
                        <div class="layui-input-block">
                            <input type="text" name="parameter" placeholder="请输入参数名称" value="" class="layui-input">
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label for="" class="layui-form-label">选择图标</label>
                        <div class="layui-input-block">
                            <input type="text" id="iconPicker" name="privilege_icon" lay-filter="iconPicker" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">target属性</label>
                        <div class="layui-input-block">
                            <input type="radio" name="target" value="_self" title="_self" checked="checked">
                            <input type="radio" name="target" value="_blank" title="_blank">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">上级权限</label>
                        <div class="layui-input-block">
                            <div id="privilegeEditParentSel" class="ew-xmselect-tree"></div>
                            <!--
                            <input type="text" name="parent_id"  placeholder="请选择上级权限" value="" class="layui-input">
                            -->
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-block">
                            <input type="number" name="orders" lay-verify="number" placeholder="请输入排序数字" value="0" class="layui-input">
                            <tip>数字越小在最前面</tip>
                        </div>
                    </div>


                </div>


            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" lay-filter="privilegeEditSubmit" lay-submit>保存</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- js部分 -->

    <script type="text/javascript" src="{{asset(__ADMIN_MODULE__.'/iconPicker/iconPicker.js')}}"></script>

    <script>

        layui.use(['jquery','layer', 'form', 'admin', 'treeTable', 'util','xmSelect','iconPicker'], function () {
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
                elem: '#privilegeTable',
                url: "{{route('privilegeList')}}",
                toolbar: ['<p>',
                    '<button lay-event="add" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>&nbsp;',
                    '</p>'].join(''),
                tree: {
                    iconIndex: 1,
                    idName: 'id',
                    pidName: 'parent_id',
                    isPidData: true,
                },
                defaultToolbar: false,
                page: false,
                cols: [[
                    {type: 'numbers'},
                    {field: 'privilege_name', minWidth: 200, title: '权限名称'},
                    {field: 'module_name', title: '模块名称'},
                    {field: 'controller_name', title: '控制器名称'},
                    {field: 'action_name', title: '方法名称'},
                    {field: 'route_url', title: 'url'},
                    {field: 'route_name', title: '路由名称'},
                    {field: 'id', title: 'ID'},
                    {field: 'orders', width: 80, align: 'center', title: '排序号'},
                    {field: 'is_menu', width: 80, align: 'center', title: '类型',hide:true},
                    {
                        width: 80, align: 'center',title: '类型',templet:function(d){
                            return d.is_menu_name;
                        }
                    },
                    {templet: '#privilegeBar', width: 120, align: 'center', title: '操作'}
                ]],

                done: function (data) {
                    tbDataList = data;
                }
            });



            /* 表格操作列点击事件 */
            treeTable.on('tool(privilegeTable)', function (obj) {
                if (obj.event === 'edit') { // 修改
                    showEditModel(obj.data);
                } else if (obj.event === 'del') { // 删除
                    doDel(obj);
                }
            });

            /* 表格头工具栏点击事件 */
            treeTable.on('toolbar(privilegeTable)', function (obj) {
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
                    maxmin: true,
                    moveOut: true,
                    area: ['100%','100%'],
                    title: (mData ? '修改' : '添加') + '权限',
                    content: $('#branchEditDialog').html(),
                    success: function (layero, dIndex) {



                        // 回显表单数据
                        form.val('privilegeEditForm', mData);

                        // 表单提交事件
                        form.on('submit(privilegeEditSubmit)', function (data) {
                            //data.field.parentId = insXmSel.getValue('valueStr')

                            var loadIndex = layer.load(2);
                            if(mData){
                                data.field.privilege_id = data.field.id;
                                delete data.field.id;
                            }else{
                                delete data.field.id;
                            }

                            $.ajax({
                                url:mData ? '{{route('privilegeEdit')}}' : '{{route('privilegeAdd')}}',
                                data:data.field,
                                type:'post',
                                dataType:'json',
                                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                                success:function(res){
                                    layer.close(loadIndex);
                                    if (res.code == 0) {
                                        layer.close(dIndex);
                                        layer.msg(res.msg, {icon: 1},function(){
                                            insTb.refresh();
                                        });

                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                }
                            });

                            return false;
                        });
                        // 渲染下拉树
                        var insXmSel = xmSelect.render({
                            el: '#privilegeEditParentSel',
                            height: 'auto',
                            data: insTb.options.data,
                            initValue: mData ? [mData.parent_id] : [0],
                            model: {label: {type: 'text'}},
                            prop: {
                                name: 'privilege_name',
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

                        //初始化图标选择
                        iconPicker.render({
                            // 选择器，推荐使用input
                            elem: '#iconPicker',
                            // 数据类型：fontClass/unicode，推荐使用fontClass
                            type: 'fontClass',
                            // 是否开启搜索：true/false
                            search: true,
                            // 是否开启分页
                            page: true,
                            // 每页显示数量，默认12
                            limit: 16,
                            // 点击回调
                            click: function (data) {
                                $('#iconPicker').val(data.icon);
                            }
                        });

                        iconPicker.checkIcon('iconPicker', 'layui-icon-app');
                        if(mData !== null && mData !== undefined) {
                            if(mData.privilege_icon !== null && mData.privilege_icon !== undefined) {
                                iconPicker.checkIcon('iconPicker', mData.privilege_icon);
                            }
                        }
                        // 弹窗不出现滚动条
                       // $(layero).children('.layui-layer-content').css('overflow', 'visible');
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
                        url:'{{route('privilegeDelete')}}',
                        data:{privilege_id: obj.data ? obj.data.id : obj.ids},
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

