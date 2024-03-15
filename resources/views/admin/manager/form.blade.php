<form id="userEditForm" lay-filter="userEditForm" class="layui-form model-form">
    <input name="id" type="hidden"/>

    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">姓名</label>
        <div class="layui-input-block">
            <input type="text" name="full_name" lay-verify="required"  lay-verType="tips" lay-reqtext="姓名不能为空" placeholder="请输入姓名" value="" class="layui-input">
            <tip>请填写姓名。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">登录账号</label>
        <div class="layui-input-block">
            <input type="text" name="account" id="accountFormItemBox" lay-verify="required" lay-verType="tips" lay-reqtext="登录账号不能为空" placeholder="请输入登录账号" value="" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item" id="passwordFormItemBox">
        <label class="layui-form-label layui-form-required">登录密码</label>
        <div class="layui-input-block">
            <input type="password" id="password" name="password" lay-verify="required" lay-verType="tips" lay-reqtext="登录密码不能为空" placeholder="请输入登录密码" value="" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">选择部门</label>
        <div class="layui-input-block">
            <div id="branchSel" class="ew-xmselect-tree"></div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">选择角色</label>
        <div class="layui-input-block" class="ew-xmselect-tree">
            <div id="roleSel"></div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input type="email" name="email" lay-verify="email" placeholder="请输入邮箱" lay-verType="tips" value=""  class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
            <input type="text" name="tel" lay-verify="phone" placeholder="请输入手机号" lay-verType="tips" value="" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="启用" checked="" class="layui-input">
            <input type="radio" name="status" value="0" title="禁用" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item text-right">
        <button class="layui-btn" lay-filter="userEditSubmit" lay-submit>保存</button>
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
    </div>
</form>

<script>
    layui.use(['layer', 'form', 'table', 'util', 'admin', 'xmSelect'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var admin = layui.admin;
        var xmSelect = layui.xmSelect;

        var mData = admin.getLayerData('#userEditForm').data;

        // 渲染下拉树
        var branchSel = xmSelect.render({
            el: '#branchSel',
            model: { label: { type: 'text'}},
            radio: true,
            name:'branch_id',
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
            tips:'请选择部门',
            height: 'auto',
            data(){
                return @json($branch)
            }
        })


        var roleSel = xmSelect.render({
            el: '#roleSel',
            name:'role_ids',
            //initValue: [3,4],
            prop: {
                name: 'role_name',
                value: 'id'
            },
            tree: {
                show: true,
                strict: true,
                indent: 15,
                expandedKeys: true,
                clickCheck: true,
                clickExpand: false,
            },

            filterable: true,
            tips:'请选择角色',
            height: 'auto',
            data(){
                return @json($roles)
            }
        })


        if(mData !== null && mData !== undefined) {
            //$('#passwordFormItemBox').remove();
            $('#accountFormItemBox').prop('disabled',true);
            $('#password').removeAttr('lay-verify');
            $.ajax({
                url:getProjectUrl() + 'admin/manager_detail',
                data:{manager_id:mData.id},
                type:'post',
                dataType:'json',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success:function(res){
                    delete res.data.password;
                    form.val('userEditForm', res.data);
                    roleSel.setValue(res.data.role_ids.map(function (item) {
                        return item;
                    }));

                    branchSel.setValue([res.data.branch_id]);
                }
            });
        } else {
            $('#passwordFormItemBox').removeAttr("hidden");
            $('#accountFormItemBox').prop('disabled',false);
        }


        form.on('submit(userEditSubmit)', function (data) {
            var loadIndex = layer.load(2);
            if(mData){
                data.field.manager_id = data.field.id;
                delete data.field.id;
            }else{
                delete data.field.id;
            }

            $.ajax({
                url:mData ? '{{route('managerEdit')}}' : '{{route('managerAdd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success:function(res){
                    layer.close(loadIndex);
                    if (res.code === 0) {
                        layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                            admin.putLayerData('formOk', true, '#userEditForm');
                            admin.closeDialog('#userEditForm');
                        });
                    } else {
                        layer.msg(res.msg, {icon: 2});
                        return false;
                    }
                }
            });

            return false;
        });
    });
</script>
