@extends('admin.layout.layout')

@section('title')
    @if(empty($encode_id)) 添加组 @else 编辑组 @endif
@endsection

@section('header')
    @if(empty($encode_id)) 添加组 @else 编辑组 @endif
@endsection

@section('description')
    @if(empty($encode_id)) 添加组 @else 编辑组 @endif
@endsection

@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="{{url('/admin/group/list')}}"><i class="fa "></i>组列表</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title"> @if(empty($encode_id)) 添加组 @else 编辑组 @endif </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-group">
            <div class="box-body">

                {{csrf_field()}}
                <input type="hidden" name="operate" value="{{$operate or 'create'}}" readonly>
                <input type="hidden" name="id" value="{{$encode_id or encode(0)}}" readonly>

                {{--类型--}}
                <div class="form-group">
                    <label class="control-label col-md-2">类别</label>
                    <div class="col-md-8 ">
                        @if(empty($data->type))
                        <select name="type" id="type" style="width:100%;" onchange="select_type()">
                            <option value="1" @if(!empty($data->type) && $data->type == 1) selected="selected" @endif>人物组</option>
                            <option value="2" @if(!empty($data->type) && $data->type == 2) selected="selected" @endif>作品组</option>
                        </select>
                        @else
                            <div class="form-control"><b>
                                @if($data->type == 1) 人物组
                                @elseif($data->type == 2) 作品组
                                @endif
                            </b></div>
                        @endif
                    </div>
                </div>

                {{--选择作者--}}
                @if(empty($encode_id))
                <div class="form-group" id="select-peoples">
                    <label class="control-label col-md-2">选择作者</label>
                    <div class="col-md-8 ">
                        <select name="peoples[]" id="peoples" style="width:100%;" multiple="multiple"></select>
                    </div>
                </div>
                @else
                @if(!empty($data->type) && $data->type == 1)
                <div class="form-group" id="select-peoples">
                    <label class="control-label col-md-2">添加作者</label>
                    <div class="col-md-8 ">
                        <select name="peoples[]" id="peoples" style="width:100%;" multiple="multiple"></select>
                    </div>
                </div>
                @endif
                @endif

                {{--选择作品--}}
                @if(empty($encode_id))
                <div class="form-group" id="select-products">
                    <label class="control-label col-md-2">选择作品</label>
                    <div class="col-md-8 ">
                        <select name="products[]" id="products" style="width:100%;" multiple="multiple"></select>
                    </div>
                </div>
                @else
                @if(!empty($data->type) && $data->type == 2)
                <div class="form-group" id="select-products">
                    <label class="control-label col-md-2">添加作品</label>
                    <div class="col-md-8 ">
                        <select name="products[]" id="products" style="width:100%;" multiple="multiple"></select>
                    </div>
                </div>
                @endif
                @endif

                {{--姓名--}}
                <div class="form-group">
                    <label class="control-label col-md-2">组名称</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="title" placeholder="请输入名称" value="{{$data->title or ''}}"></div>
                    </div>
                </div>
                {{--说明--}}
                <div class="form-group">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}"></div>
                    </div>
                </div>
                {{--内容--}}
                <div class="form-group">
                    <label class="control-label col-md-2">介绍详情</label>
                    <div class="col-md-8 ">
                        <div>
                            @include('UEditor::head')
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="content" type="text/plain">{!! $data->content or '' !!}</script>
                            <!-- 实例化编辑器 -->
                            <script type="text/javascript">
                                var ue = UE.getEditor('container');
                                ue.ready(function() {
                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                });
                            </script>
                        </div>
                    </div>
                </div>
                {{--cover 封面图片--}}
                @if(!empty($data->cover_pic))
                    <div class="form-group">
                        <label class="control-label col-md-2">封面图片</label>
                        <div class="col-md-8 ">
                            <div class="edit-img"><img src="{{url('http://cdn.'.$_SERVER['HTTP_HOST'].'/'.$data->cover_pic.'?'.rand(0,999))}}" alt=""></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">更换封面图片</label>
                        <div class="col-md-8 ">
                            <div><input type="file" name="cover" placeholder="请上传封面图片"></div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label class="control-label col-md-2">上传封面图片</label>
                        <div class="col-md-8 ">
                            <div><input type="file" name="cover" placeholder="请上传封面图片"></div>
                        </div>
                    </div>
                @endif

            </div>
            </form>

            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-primary" id="edit-group-submit"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection




@section('style')
    <link href="https://cdn.bootcss.com/select2/4.0.5/css/select2.min.css" rel="stylesheet">
@endsection


@section('js')
<script src="https://cdn.bootcss.com/select2/4.0.5/js/select2.min.js"></script>
<script>
    $(function() {

        select_type();

        // 修改幻灯片信息
        $("#edit-group-submit").on('click', function() {
            var options = {
                url: "/admin/group/edit",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "/admin/group/list";
                    }
                }
            };
            $("#form-edit-group").ajaxSubmit(options);
        });

        $('#peoples').select2({
            ajax: {
                url: "/admin/group/select2_peoples",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
//                    console.log(data);
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

        $('#products').select2({
            ajax: {
                url: "/admin/group/select2_products",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
//                    console.log(data);
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

    });

    function select_type()
    {
        var vs = $('select#type option:selected').val();
        if(vs == 1)
        {
            $('#select-peoples').show();
            $('#select-products').hide();
        }
        else if(vs == 2)
        {
            $('#select-peoples').hide();
            $('#select-products').show();
        }
        $("#type").val(vs);
    }
</script>
@endsection
