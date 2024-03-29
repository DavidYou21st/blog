<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>404</title>
    <link rel="stylesheet" href="{{asset(__ADMIN_LIBS__.'/layui/css/layui.css')}}"/>
    <link rel="stylesheet" href="{{asset(__ADMIN_MODULE__.'/admin.css?v=318')}}"/>
</head>
<body>





<!-- 正文开始 -->
<div class="error-page">
    <img class="error-page-img" src="/static/admin/images/ic_404.png">
    <div class="error-page-info">
        <h1>{{$code}}</h1>
        <p>很抱歉，{{$message}}(⋟﹏⋞)</p>
        <div>
            <a ew-href="{{route('admin.home')}}" class="layui-btn">返回首页</a>
        </div>
    </div>
</div>
<style>
    .error-page {
        position: absolute;
        top: 50%;
        width: 100%;
        text-align: center;
        -o-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    .error-page .error-page-img {
        display: inline-block;
        height: 260px;
        margin: 10px 15px;
    }

    .error-page .error-page-info {
        vertical-align: middle;
        display: inline-block;
        margin: 10px 15px;
    }

    .error-page .error-page-info > h1 {
        color: #434e59;
        font-size: 72px;
        font-weight: 600;
    }

    .error-page .error-page-info > p {
        color: #777;
        font-size: 20px;
        margin-top: 5px;
    }

    .error-page .error-page-info > div {
        margin-top: 30px;
    }
</style>

<!-- js部分 -->
<script type="text/javascript" src="{{asset(__ADMIN_LIBS__.'/layui/layui.js?v=318')}}"></script>
<script type="text/javascript" src="{{asset(__ADMIN_JS__.'/common.js?v=318')}}"></script>
<script>
    layui.use(['admin'], function () {

    });
</script>
</body>
</html>