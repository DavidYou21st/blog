@extends('admin/layouts.main')

@section('title','部门列表')

@section('style')

@endsection

@section('content')
<div class="layuimini-container">
    <div class="layuimini-main">

    </div>
</div>

<div class="orgWrap" id="orgWrap">
</div>
@endsection


@section('js')
    <script src="{{URL::asset('static/admin/js/lay-config.js?v=1.0.4')}}" charset="utf-8"></script>
    <script>
        layui.use(['jquery', 'orgChart'], function () {
            var $ = layui.jquery,
                orgChart = layui.orgChart;
                data = @json($branch);


            orgChart.render({
                elm: '#orgWrap',
                data: data,
                drag: true,
                depth: 3,
                renderdata: function(data,$dom){
                var value = data;
                if(value && Object.keys(value).length) {
                    var $name = $('<div class="name"></div>');
                    !!(value.name) && $name.text(value.name);
                    $dom.append($name)
                    $dom.addClass('organization')
                }
            },
            callback: function() {}
        })
     });

    </script>
@endsection

