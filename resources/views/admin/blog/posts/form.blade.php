<form id="postsEditForm" lay-filter="postsEditForm" class="layui-form model-form">
    <input name="id" type="hidden"/>
    {{ csrf_field() }}
    <div class="layui-form-item" id="passwordFormItemBox">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.title')</label>
        <div class="layui-input-block">
            <input type="text" id="title" name="title" lay-verify="required" lay-verType="tips" lay-reqtext="标题不能为空" placeholder="请输入标题" value="" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.category')</label>
        <div class="layui-input-block" class="ew-xmselect-tree">
            <div id="categorySel"></div>
        </div>
    </div>
    <div class="layui-form-item" id="passwordFormItemBox">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.summary')</label>
        <div class="layui-input-block">
            <input type="text" id="summary" name="summary" value="" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" id="passwordFormItemBox">
        <label class="layui-form-label layui-form-required">@lang('admin/_globals.forms.content')</label>
        <div class="layui-input-block">
            <input type="text" id="description" name="description" value="" class="layui-input">
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
        <button class="layui-btn" lay-filter="postsEditSubmit" lay-submit>保存</button>
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

        var mData = admin.getLayerData('#postsEditForm').data;

        // 渲染下拉树
        var categorySel = xmSelect.render({
            el: '#categorySel',
            model: { label: { type: 'text'}},
            radio: true,
            name:'category_id',
            clickClose: true,
            prop: {
                name: 'title',
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
            tips:'请选择分类',
            height: 'auto',
            data(){
                return @json($categories)
            }
        })

        if(mData !== null && mData !== undefined) {
            $.ajax({
                url:'{{route('blogPostsDetail')}}',
                data:{id:mData.id},
                type:'post',
                dataType:'json',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success:function(res){
                    form.val('postsEditForm', res.data);
                    categorySel.setValue([res.data.category_id]);
                }
            });
        }

        form.on('submit(postsEditSubmit)', function (data) {
            var loadIndex = layer.load(2);
            $.ajax({
                url:mData ? '{{route('blogPostsEdit')}}' : '{{route('blogPostsAdd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success:function(res){
                    layer.close(loadIndex);
                    if (res.code === 0) {
                        layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                            admin.putLayerData('formOk', true, '#postsEditForm');
                            admin.closeDialog('#postsEditForm');
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
