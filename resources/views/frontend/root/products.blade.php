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

    <div class="col-xs-12 col-sm-12 col-md-3 hidden-xs hidden-sm container-body-right">

        @include('frontend.component.sidebar')

    </div>

</div>
@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
