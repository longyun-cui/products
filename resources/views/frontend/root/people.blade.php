@extends('frontend.layout.layout')

@section('title') {{$people->name}} @endsection
@section('header') {{$people->name}} @endsection
@section('description','人物介绍')
@section('breadcrumb')
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div style="display:none;">
    <input type="hidden" id="_id" value="{{$_encode or ''}}" readonly>
</div>

<div class="container">

    <div class="col-xs-12 col-sm-12 col-md-9 container-body-left">

        {{--作者--}}
        @include('frontend.component.peoples', ['datas' => $peoples])

        <div class="content-header box-body" style="margin-top:32px;margin-bottom:16px;padding:0;">
            <h1><small class=""><b>{{$people->name_ or ''}} 作品集</b></small></h1>
        </div>

        {{--作品集--}}
        @include('frontend.component.products', ['datas' => $people->products])

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
