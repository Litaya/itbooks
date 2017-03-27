@extends('admin.layouts.frame')

@section('title','书圈-微信素材管理')
@section('content')
    <style>
        #pages{
            text-align: center;
        }
        #pages > .item{
            padding:5px 10px;
            color: #999;
            background-color: white;
            box-shadow: 1px 1px 3px #ccc;
        }
        #pages > .active{
            padding:5px 10px;
            color: #999;
            background-color: #FAFAFA;
            box-shadow: inset 0 0 3px #ccc;
        }
    </style>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-12">
            <small style="color:gray">今日阅读:23 &nbsp;&nbsp;今日评论:12</small>
            <button class="btn btn-success btn-sm" onclick="syncNews('{{ route('admin.material.sync') }}')" id="btn-sync" style="position: absolute; right: 10px;">同步列表</button>
            <script type="text/javascript">
                function syncNews($url) {
                    $("#btn-sync").attr('disabled','disabled');
                    $("#btn-sync").html('正在同步...');
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        method:'post',
                        url:$url,
                        data: {_token: CSRF_TOKEN},
                        success:function () {
                            $("#btn-sync").removeAttr('disabled');
                            $("#btn-sync").html('同步列表');
                            location.reload();
                        }
                    });
                }
            </script>
        </div>
    </div>
    @foreach($materials as $material)
        <div class="row" style="background-color: #ffffff; box-shadow:0 0 5px #ccc;margin-bottom: 10px;">
            <div class="col-lg-2" style="padding-left: 0;">
                <a href="javascript:void(0)"><img src="{{ $material->cover_path }}" alt="" height="100px;" width="100%;"></a>
            </div>
            <div class="col-lg-10" style="padding: 10px 0 0 0;height: 100px;">
                <p><a href="{{ route('admin.material.show',$material->id) }}">{{ $material->title }}</a></p>
                <small>{{ $material->digest }}</small>
                <br>
                <small style="position: absolute;bottom:5px; color:#ccc">阅读: {{ $material->reading_quantity }}&nbsp; 评论: <?php echo sizeof($material->comments) ?></small>
                <small style="position: absolute;bottom:5px; right: 15px; color:#ccc">{{ $material->wechat_update_time }}</small>
            </div>
        </div>
    @endforeach

    <div class="row" id="pages">
        {{ $materials->links() }}
    </div>
@endsection
