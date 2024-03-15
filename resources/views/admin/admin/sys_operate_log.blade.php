@extends('admin.public.admin_base')

@section('title','操作日志')
@section('content')
    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <!-- 表格工具栏 -->
                <form class="layui-form toolbar">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">用户账号:</label>
                            <div class="layui-input-inline">
                                <input name="account" class="layui-input" placeholder="输入账号"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">登录日期:</label>
                            <div class="layui-input-inline">
                                <input name="loginRecordDateSel" class="layui-input icon-date" placeholder="选择日期范围"
                                       autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-inline">&emsp;
                            <button class="layui-btn icon-btn" lay-filter="loginRecordTbSearch" lay-submit>
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>&nbsp;
                            <button id="loginRecordExportBtn" class="layui-btn icon-btn" type="button">
                                <i class="layui-icon">&#xe67d;</i>导出
                            </button>
                        </div>
                    </div>
                </form>
                <!-- 数据表格 -->
                <table id="loginRecordTable" lay-filter="loginRecordTable"></table>
                <script type="text/html" id="currentTableBar">
                    <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
                </script>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- js部分 -->
    <script>
        layui.use(['layer', 'form', 'table', 'util', 'laydate', 'tableX'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            var util = layui.util;
            var laydate = layui.laydate;
            var tableX = layui.tableX;

            /* 渲染表格 */
            var insTb = table.render({
                elem: '#loginRecordTable',
                url: "{{route('sysOperateLog')}}",
                page: true,
                cellMinWidth: 80,
                cols: [[
                    {field: 'id', title: 'ID', sort: true},
                    {field: 'title', title: '动作'},
                    {field: 'url',  title: '请求地址'},
                    {field: 'username',  title: '操作人'},
                    {field: 'method',  title: '方式'},
                    {field: 'request_params',  title: '请求参数'},
                    {field: 'response_params', with:600, title: '响应信息'},
                    {field: 'message',  title: '提示信息'},
                    {field: 'operating_system',  title: '系统'},
                    {field: 'useragent',  title: '浏览器'},
                    {field: 'ip',  title: 'ip'},
                    {field: 'create_time', title: '日期'},
                    {title: '操作', toolbar: '#currentTableBar', align: "center"}
                ]],
            });

            /* 渲染时间选择 */
            laydate.render({
                elem: 'input[name="loginRecordDateSel"]',
                type: 'date',
                range: true,
                trigger: 'click'
            });

            /* 表格搜索 */
            form.on('submit(loginRecordTbSearch)', function (data) {
                if (data.field.loginRecordDateSel) {
                    var searchDate = data.field.loginRecordDateSel.split(' - ');
                    data.field.startDate = searchDate[0];
                    data.field.endDate = searchDate[1];
                } else {
                    data.field.startDate = null;
                    data.field.endDate = null;
                }
                data.field.loginRecordDateSel = undefined;
                insTb.reload({where: data.field, page: {curr: 1}});
                return false;
            });

            table.on('tool(loginRecordTable)', function (obj) {
                var data = obj.data;
                if (obj.event === 'delete') {
                    var log_id = data.id;
                    layer.confirm('真的删除行么', function (index) {
                        $.ajax({
                            url:"{{route('deleteSysOperateLog')}}",
                            dataType:'json',
                            type:'post',
                            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                            data: {log_id: log_id},
                            success:function(data){
                                layer.closeAll();
                                if(data.code==0){
                                    obj.del();
                                    insTb.reload();
                                    return false;
                                }else{
                                    layer.msg(data.msg,{icon:2});
                                    return false;
                                }
                            },
                            error:function(res){

                            }
                        });

                    });
                }
            });

            /* 导出excel */
            $('#loginRecordExportBtn').click(function () {
                var checkRows = table.checkStatus('loginRecordTable');
                if (checkRows.data.length === 0) {
                    layer.msg('请选择要导出的数据', {icon: 2});
                } else {
                    tableX.exportDataX({
                        cols: insTb.config.cols,
                        data: checkRows.data,
                        fileName: '登录日志'
                    });
                }
            });

        });
    </script>
@endsection
