<form id="categoryEditForm" lay-filter="categoryEditForm" class="layui-form model-form">
    <input name="id" type="hidden"/>
    {{ csrf_field() }}
    <div class="layui-form-item" id="passwordFormItemBox">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.category')</label>
        <div class="layui-input-block">
            <input type="text" id="title" name="title" lay-verify="required" lay-verType="tips" lay-reqtext="名称不能为空" placeholder="请输入名称" value="" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" id="passwordFormItemBox">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.order')</label>
        <div class="layui-input-block">
            <input type="text" id="order" name="order" value="" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.status')</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="启用" checked="" class="layui-input">
            <input type="radio" name="status" value="0" title="禁用" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item text-right">
        <button class="layui-btn" lay-filter="categoryEditSubmit" lay-submit>保存</button>
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
    </div>
</form>

<script>
    layui.use(['layer', 'form', 'table', 'util', 'admin', 'xmSelect'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var admin = layui.admin;

        var mData = admin.getLayerData('#categoryEditForm').data;

        if(mData !== null && mData !== undefined) {
            $.ajax({
                url:'{{route('blogCategoriesDetail')}}',
                data:{id:mData.id},
                type:'post',
                dataType:'json',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success:function(res){
                    form.val('categoryEditForm', res.data);
                }
            });
        }

        form.on('submit(categoryEditSubmit)', function (data) {
            var loadIndex = layer.load(2);
            $.ajax({
                url:mData ? '{{route('blogCategoriesEdit')}}' : '{{route('blogCategoriesAdd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success:function(res){
                    layer.close(loadIndex);
                    if (res.code === 0) {
                        layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                            admin.putLayerData('formOk', true, '#categoryEditForm');
                            admin.closeDialog('#categoryEditForm');
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
