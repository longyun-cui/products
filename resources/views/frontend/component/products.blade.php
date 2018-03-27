{{--products--}}
@foreach($datas as $num => $data)
<div class="item-piece item-option product-option">

    <!-- BEGIN PORTLET-->
    <div class="boxe panel-default item-entity-container">

        <div class="box-body item-title-row">
            <span><a href="{{url('/product?id='.encode($data->id))}}" target="_blank">{{$data->title or ''}}</a></span>
        </div>

        <div class="box-body item-info-row">
            <span>【{{ $data->category or '未知' }}】</span>
            <span>【{{ $data->time or '未知' }}】</span>
        </div>

        @if(count($data->peoples))
        <div class="box-body item-peoples-row">
            @foreach($data->peoples as $p)
                <span><a href="{{url('/people?id='.encode($p->id))}}" target="_blank">{{$p->name or '未知'}}</a></span><br>
            @endforeach
        </div>
        @endif

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