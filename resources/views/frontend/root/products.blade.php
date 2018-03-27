@extends('frontend.layout.layout')

@section('title','作品集')
@section('header','作品集')
@section('description','作品集')
@section('breadcrumb')
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div style="display:none;">
    <input type="hidden" id="_id" value="{{$_encode or ''}}" readonly>
</div>


<div class="container">

    <div class="col-sm-12 col-md-9 container-body-left">

        @include('frontend.component.products', ['datas' => $products])
        {{ $products->links() }}

    </div>

    <div class="col-sm-12 col-md-3 hidden-xs hidden-sm container-body-right">

        <div class="box-body right-menu" style="background:#fff;">

            <a href="{{url('/peoples')}}">
                <div class="box-body {{ $people_active or '' }}">
                    <i class="fa fa-list text-orange"></i> <span>&nbsp; 人物集</span>
                </div>
            </a>

            <a href="{{url('/products')}}">
                <div class="box-body {{ $product_active or '' }}">
                    <i class="fa fa-list text-orange"></i> <span>&nbsp; 作品集</span>
                </div>
            </a>

        </div>

        <div class="box-body right-home" style="margin-top:16px;background:#fff;">

            @if(!Auth::guard('admin')->check())
                <a href="{{url('/login')}}">
                    <div class="box-body">
                        <i class="fa fa-circle-o text-blue"></i> <span>&nbsp; 登录</span>
                    </div>
                </a>
                <a href="{{url('/register')}}">
                    <div class="box-body">
                        <i class="fa fa-circle-o text-blue"></i> <span>&nbsp; 注册</span>
                    </div>
                </a>
            @else
                <a href="{{url('/admin')}}">
                    <div class="box-body">
                        <i class="fa fa-home text-blue"></i> <span>&nbsp; 返回我的后台</span>
                    </div>
                </a>
            @endif

        </div>

    </div>

</div>
@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
