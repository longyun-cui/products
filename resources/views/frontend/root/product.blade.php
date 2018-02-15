@extends('frontend.layout.layout')

@section('title') {{$data->name}} @endsection
@section('header') {{$data->name}} @endsection
@section('description','作品')
@section('breadcrumb')
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div style="display:none;">
    <input type="hidden" id="chart_id" value="{{$chart_encode or ''}}" readonly>
    <input type="hidden" id="table_id" value="{{$table_encode or ''}}" readonly>
</div>

{{--作者--}}
<div class="row">
    <div class="col-md-12">
        <div class="box panel-default box-info">

            <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                <h3 class="box-title">{{$data->title}}</h3>
                <span>【{{ $data->category or '未知' }}】</span>
                <span>【{{ $data->time or '未知' }}】</span>
                <span><a href="{{url('/people?id='.encode($data->people_id))}}" target="_blank">{{$data->people->name or '未知'}}</a></span>
            </div>

            @if(!empty($data->description))
                <div class="box-body">
                    <div class="colo-md-12 text-muted"> {{ $data->description or '' }} </div>
                </div>
            @endif

            @if(!empty($data->content))
                <div class="box-body">
                    <div class="colo-md-12"> {!! $data->content or '' !!}  </div>
                </div>
            @endif

            <div class="box-footer">
                &nbsp;
            </div>

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
