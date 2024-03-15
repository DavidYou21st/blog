@extends('admin.public.admin_base')

@section('title','部门列表')

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
                            <button class="layui-btn icon-btn" lay-filter="authoritiesTbSearch" lay-submit>
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>&nbsp;
                        </div>
                    </div>
                </form>
                -->
                <!-- 数据表格 -->
                <table id="branchTable"></table>
            </div>
        </div>
    </div>
@endsection


@section('js')


    <!-- 表格操作列 -->
    <script type="text/html" id="branchTbBar">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <!-- 表单弹窗 -->
    <script type="text/html" id="branchEditDialog">
        <form id="authoritiesEditForm" lay-filter="authoritiesEditForm" class="layui-form model-form"
              style="padding-right: 20px;">
            <input name="id" type="hidden"/>
            <div class="layui-row">
                <div class="layui-col-md12">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">部门名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="branch_name" lay-verify="required" lay-reqtext="部门名称不能为空" placeholder="请输入部门名称" value="" lay-verType="tips"  class="layui-input" >
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
                        <label class="layui-form-label layui-form-required">上级部门</label>
                        <div class="layui-input-block">
                            <div id="branchEditParentSel" class="ew-xmselect-tree"></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-block">
                            <input type="text" name="orders" placeholder="请输入排序数字" value="0" class="layui-input">
                            <tip>数字越小在最前面</tip>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" lay-filter="branchEditSubmit" lay-submit>保存</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- js部分 -->


    <script>
        layui.use(['layer', 'form', 'admin', 'treeTable', 'util', 'xmSelect'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var admin = layui.admin;
            var treeTable = layui.treeTable;
            var util = layui.util;
            var C = layui.common;
            var xmSelect = layui.xmSelect;
            var tbDataList = [];


            // 渲染表格
            var insTb = treeTable.render({
                elem: '#branchTable',
                url: "{{route('branchList')}}",
                toolbar: ['<p>',
                    '<button lay-event="add" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>&nbsp;',
                    '</p>'].join(''),
                tree: {
                    iconIndex: 1,
                    idName: 'id',
                    pidName: 'parent_id',
                    isPidData: true,
                },

                @verbatim

                cols: [[
                    {type: 'numbers'},
                    {field: 'branch_name', minWidth: 200, title: '部门名称'},
                    {field: 'type', title: '类型'},
                    {field: 'id', title: 'ID'},
                    {field: 'orders',align: 'center', title: '排序号'},
                    {templet: '#branchTbBar',  align: 'center', title: '操作'}
                ]],
                @endverbatim
                done: function (data) {
                    tbDataList = data;
                }
            });



            /* 表格操作列点击事件 */
            treeTable.on('tool(branchTable)', function (obj) {
                if (obj.event === 'edit') { // 修改
                    showEditModel(obj.data);
                } else if (obj.event === 'del') { // 删除
                    doDel(obj);
                }
            });

            /* 表格头工具栏点击事件 */
            treeTable.on('toolbar(branchTable)', function (obj) {
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
            form.on('submit(authoritiesTbSearch)', function (data) {
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
                    title: (mData ? '修改' : '添加') + '权限',
                    content: $('#branchEditDialog').html(),
                    success: function (layero, dIndex) {
                        // 回显表单数据
                        form.val('authoritiesEditForm', mData);
                        // 表单提交事件
                        form.on('submit(branchEditSubmit)', function (data) {
                            //data.field.parentId = insXmSel.getValue('valueStr')

                            var loadIndex = layer.load(2);
                            if(mData){
                                data.field.branch_id = data.field.id;
                                delete data.field.id;
                            }else{
                                delete data.field.id;
                            }

                            $.ajax({
                                url:mData ? '{{route('branchEdit')}}' : '{{route('branchAdd')}}',
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
                            el: '#branchEditParentSel',
                            height: 'auto',
                            data: insTb.options.data,
                            initValue: mData ? [mData.parent_id] : [0],
                            model: {label: {type: 'text'}},
                            prop: {
                                name: 'branch_name',
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
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
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
                        url:'{{route('branchDelete')}}',
                        data:{branch_id: obj.data ? obj.data.id : obj.ids},
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

