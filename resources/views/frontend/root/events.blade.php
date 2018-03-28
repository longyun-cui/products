@extends('frontend.layout.layout')

@section('title','人物集')
@section('header','人物集')
@section('description','人物集')

@section('content')
<div style="display:none;">
    <input type="hidden" id="_id" value="{{$_encode or ''}}" readonly>
</div>

<div class="container">

    <div class="col-xs-12 col-sm-12 col-md-9 container-body-left">

        @include('frontend.component.events', ['datas' => $events])
        {{ $events->links() }}

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
