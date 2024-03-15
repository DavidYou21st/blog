<div class="layui-card-header">本地便签</div>
<div class="note-wrapper"></div>
<div class="note-empty">
    <i class="layui-icon layui-icon-face-surprised"></i>
    <p>没有便签</p>
</div>
<div class="btn-circle" id="noteAddBtn" title="添加便签" style="position: absolute;">
    <i class="layui-icon layui-icon-add-1"></i>
</div>

<script>
    layui.use(['layer', 'form', 'util', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var util = layui.util;
        var admin = layui.admin;
        var dataList = [];  // 便签列表
        var $noteWrapper = $('.note-wrapper');

        /* 渲染列表 */
        function renderList() {
            $noteWrapper.empty();
            dataList = layui.data(admin.setter.tableName).notes;
            if (dataList === undefined) dataList = [];
            for (var i = 0; i < dataList.length; i++) {
                var item = dataList[i];
                $noteWrapper.prepend([
                    '<div class="note-item" data-id="', item.id, '">',
                    '   <div class="note-item-content">', util.escape(item.content), '</div>',
                    '   <div class="note-item-time">', item.time, '</div>',
                    '   <i class="layui-icon layui-icon-close-fill note-item-del"></i>',
                    '</div>'
                ].join(''));
            }
            $('.note-empty').css('display', dataList.length === 0 ? 'block' : 'none');
            // 点击修改
            $('.note-item').click(function () {
                var index = parseInt($(this).attr('data-id'));
                showNote(dataList[index]);
            });
            // 点击删除
            $('.note-item-del').click(function (e) {
                var id = parseInt($(this).parent().attr('data-id'));
                layer.confirm('确认删除吗？', {
                    skin: 'layui-layer-admin',
                    shade: .1,
                    shadeClose: true
                }, function (index) {
                    layer.close(index);
                    dataList.splice(id, 1);
                    for (var i = 0; i < dataList.length; i++) dataList[i].id = i;
                    putDataList();
                    renderList();
                });
                e.stopPropagation();
            });
        }

        renderList();

        /* 添加 */
        $('#noteAddBtn').click(function () {
            showNote();
        });

        // 显示编辑弹窗
        function showNote(data) {
            var id = data ? data.id : undefined, content = data ? data.content : '';
            admin.open({
                id: 'layer-note-edit',
                title: '便签',
                type: 1,
                area: 'auto',
                offset: '50px',
                shadeClose: true,
                content: '<textarea id="noteEditText" placeholder="请输入内容" style="width: 280px;height: 150px;border: none;color: #666666;word-wrap: break-word;padding: 10px 20px;resize: vertical;">' + content + '</textarea>',
                success: function () {
                    $('#noteEditText').change(function () {
                        content = $(this).val();
                    });
                },
                end: function () {
                    if (id !== undefined) {
                        if (!content) {
                            dataList.splice(id, 1);
                            for (var i = 0; i < dataList.length; i++) dataList[i].id = i;
                        } else if (content !== dataList[id].content) {
                            dataList[id].content = content;
                            dataList[id].time = util.toDateString(new Date(), 'yyyy-MM-dd HH:mm');
                        }
                    } else if (content) {
                        dataList.push({
                            id: dataList.length, content: content,
                            time: util.toDateString(new Date(), 'yyyy-MM-dd HH:mm')
                        });
                    }
                    putDataList();
                    renderList();
                }
            });
        }

        /* 更新本地缓存 */
        function putDataList() {
            layui.data(admin.setter.tableName, {key: 'notes', value: dataList});
        }

    });
</script>

<style>
    .note-wrapper {
        padding: 15px 0 15px 15px;
        background-color: #fbfbfb;
        position: absolute;
        top: 43px;
        left: 0;
        right: 0;
        bottom: 0;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    .note-wrapper .note-item {
        display: inline-block;
        width: 110px;
        padding: 12px;
        cursor: pointer;
        position: relative;
        border-radius: 8px;
        margin: 0 15px 15px 0;
        border: 1px solid #eeeeee;
        background-color: #ffffff;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-transition: all .3s ease;
        -moz-transition: all .3s ease;
        -ms-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .note-wrapper .note-item:hover {
        box-shadow: 0 0 8px rgba(0, 0, 0, .05);
        -webkit-transform: scale(1.02);
        -moz-transform: scale(1.02);
        -ms-transform: scale(1.02);
        -o-transform: scale(1.02);
        transform: scale(1.02);
    }

    .note-wrapper .note-item .note-item-content {
        color: #666;
        height: 80px;
        font-size: 14px;
        overflow: hidden;
        word-break: break-all;
    }

    .note-wrapper .note-item .note-item-time {
        color: #999;
        font-size: 12px;
        margin-top: 8px;
    }

    .note-wrapper .note-item .note-item-del {
        position: absolute;
        top: 2px;
        right: 2px;
        color: #FF5722;
        font-size: 24px;
        height: 24px;
        width: 24px;
        background-color: #fff;
        border-radius: 50%;
        visibility: hidden;
        -webkit-transition: all .3s ease;
        -moz-transition: all .3s ease;
        -ms-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
        opacity: 0;
    }

    .note-wrapper .note-item:hover .note-item-del {
        visibility: visible;
        opacity: 1;
    }

    .note-empty {
        color: #999;
        padding: 80px 0;
        text-align: center;
        display: none;
        position: relative;
        z-index: 1
    }

    .note-empty .layui-icon {
        font-size: 60px;
        margin-bottom: 10px;
        display: inline-block;
    }
</style>