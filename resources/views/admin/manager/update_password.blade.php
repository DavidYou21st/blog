<!-- 修改密码表单 -->
<form class="layui-form model-form">
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">原始密码:</label>
        <div class="layui-input-block">
            <input type="password" name="old_password" placeholder="请输入原始密码" class="layui-input"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">新密码:</label>
        <div class="layui-input-block">
            <input type="password" name="password" placeholder="请输入新密码" class="layui-input"
                   lay-verType="tips" lay-verify="required|psw" required/>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">确认密码:</label>
        <div class="layui-input-block">
            <input type="password" name="confirm_password" placeholder="请再次输入新密码" class="layui-input"
                   lay-verType="tips" lay-verify="required|equalTo" lay-equalTo="input[name=password]" required/>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block text-right">
            <button class="layui-btn" lay-filter="submit-psw" lay-submit>保存</button>
            <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        </div>
    </div>
</form>

<!-- js部分 -->
<script>
    layui.use(['layer', 'form', 'admin', 'formX'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var admin = layui.admin;

        // 监听提交
        form.on('submit(submit-psw)', function (data) {
            //layer.msg(JSON.stringify(data.field));
            var postData = data.field;
            $.ajax({
                url:"{{route('updatePassword')}}",
                dataType:'json',
                type:'post',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                data:postData,
                success:function(data){
                    if(data.code==0){
                        admin.closeDialog('[lay-filter="submit-psw"]');
                        layer.msg('密码修改成功', function () {
                            window.location = '/admin/login';
                        });
                        return false;
                    }else{
                        layer.msg(data.msg,{icon:2});
                        return false;
                    }
                },
                error:function(res){

                }
            });

            return false;
        });

    });
</script>