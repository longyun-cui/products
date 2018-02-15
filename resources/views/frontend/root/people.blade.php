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
    <input type="hidden" id="chart_id" value="{{$chart_encode or ''}}" readonly>
    <input type="hidden" id="table_id" value="{{$table_encode or ''}}" readonly>
</div>

{{--作者--}}
<div class="row">
    <div class="col-md-12">
        <div class="box panel-default box-info">

            <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                <h3 class="box-title">{{$people->name}}</h3>
                @if(!empty($people->nation)) <span>【{{$people->nation or ''}}】</span> @endif
                <span>【{{$people->birth or '未知'}} - {{$people->death or '至今'}}】</span>
                <span>【{{$people->major or '未知'}}】</span>
            </div>

            @if(!empty($people->description))
                <div class="box-body">
                    <div class="colo-md-12 text-muted"> {{ $people->description or '' }} </div>
                </div>
            @endif

            @if(!empty($people->content))
                <div class="box-body">
                    <div class="colo-md-12"> {!! $people->content or '' !!}  </div>
                </div>
            @endif

            <div class="box-footer">
                &nbsp;
            </div>

        </div>
    </div>
</div>


<section class="content-header" style="margin-top:32px;margin-bottom:32px;padding:0;">
    <h1>{{$people->name}} <small class=""><b>作品集</b></small></h1>
</section>


{{--作品集--}}
@foreach($people->products as $num => $data)
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default
                @if($loop->index % 7 == 0) box-primary
                @elseif($loop->index % 7 == 1) box-danger
                @elseif($loop->index % 7 == 2) box-success
                @elseif($loop->index % 7 == 3) box-warning
                @elseif($loop->index % 7 == 4) box-default
                @elseif($loop->index % 7 == 5) box-primary
                @elseif($loop->index % 7 == 6) box-info
                @endif
            ">

                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title"><a href="{{url('/product?id='.encode($data->id))}}" target="_blank">{{$data->title}}</a></h3>
                    <span>【{{$data->category or '未知'}}】</span>
                    <span>【{{$data->time or '未知'}}】</span>
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
            <!-- END PORTLET-->
        </div>
    </div>
@endforeach

@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
