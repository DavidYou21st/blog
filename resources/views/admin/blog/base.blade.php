@extends('admin.base')

@section('content')

    <div class="page-heading">
    	<div class="pull-right">
	    	@yield('actions')
	    </div>
        <h2>@lang('admin/blog.module') <small><i class="fa fa-angle-right"></i> @yield('subtitle')</small></h2>
    </div>
    
    @yield('blog')

@endsection