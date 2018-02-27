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
    <input type="hidden" id="chart_id" value="{{$chart_encode or ''}}" readonly>
    <input type="hidden" id="table_id" value="{{$table_encode or ''}}" readonly>
</div>

{{--作品--}}
@foreach($datas as $num => $data)
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default
                @if($loop->index % 7 == 0) box-info
                @elseif($loop->index % 7 == 1) box-danger
                @elseif($loop->index % 7 == 2) box-success
                @elseif($loop->index % 7 == 3) box-default
                @elseif($loop->index % 7 == 4) box-warning
                @elseif($loop->index % 7 == 5) box-primary
                @elseif($loop->index % 7 == 6) box-danger
                @endif
            ">

                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title"><a href="{{url('/product?id='.encode($data->id))}}" target="_blank">{{$data->title or ''}}</a></h3>
                    <span>【{{ $data->category or '未知' }}】</span>
                    <span>【{{ $data->time or '未知' }}】</span>
                    @foreach($data->peoples as $people)
                        <span><a href="{{url('/people?id='.encode($people->id))}}" target="_blank">【{{$people->name or '未知'}}】</a></span>
                    @endforeach
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

{{ $datas->links() }}

@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
