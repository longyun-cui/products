{{--peoples--}}
@foreach($datas as $num => $data)
<div class="item-piece item-option people-option">

    <!-- BEGIN PORTLET-->
    <div class="boxe panel-default item-entity-container">

        <div class="box-body item-title-row">
            <span><a href="{{url('/people?id='.encode($data->id))}}" target="_blank">{{$data->name or ''}}</a></span>
        </div>

        <div class="box-body item-info-row">
            @if(!empty($data->nation)) <span>【{{$data->nation or ''}}】</span> @endif
            <span>【{{$data->birth or '未知'}} ~ {{$data->death or '至今'}}】</span>
            <span>【{{$data->major or '未知'}}】</span>
        </div>

        @if(!empty($data->description))
            <div class="box-body item-description-row text-muted">
                <div class="colo-md-12 text-muted"> {{ $data->description or '' }} </div>
            </div>
        @endif

        @if(!empty($data->content))
            <div class="box-body item-content-row">
                <article class="colo-md-12"> {!! $data->content or '' !!}  </article>
            </div>
        @endif

        <div class="box-footer">
            &nbsp;
        </div>

    </div>
    <!-- END PORTLET-->

</div>
@endforeach