@extends('frontend.layout.layout')

@section('title','人物集')
@section('header','人物集')
@section('description','人物集')

@section('content')
<div style="display:none;">
    <input type="hidden" id="chart_id" value="{{$chart_encode or ''}}" readonly>
    <input type="hidden" id="table_id" value="{{$table_encode or ''}}" readonly>
</div>

{{--作者--}}
@foreach($datas as $num => $data)
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="boxe panel-default">

                <div class="box-header with-border panel-heading">
                    <h3 class="box-title"><a href="{{url('/people?id='.encode($data->id))}}" target="_blank">{{$data->name or ''}}</a></h3>
                    @if(!empty($data->nation)) <span>【{{$data->nation or ''}}】</span> @endif
                    <span>【{{$data->birth or '未知'}} - {{$data->death or '至今'}}】</span>
                    <span>【{{$data->major or '未知'}}】</span>
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
