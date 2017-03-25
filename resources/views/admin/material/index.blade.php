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
            <form action="{{ route('admin.material.sync') }}" method="post" style="display: inline">
                {{ csrf_field() }}
                <input type="submit" href="javascript:void(0)"href="javascript:void(0)" class="btn btn-success btn-sm" style="position: absolute; right: 10px;" value="同步列表"/>
            </form>
        </div>
    </div>
    @foreach($materials as $material)
        <div class="row" style="background-color: #ffffff; box-shadow:0 0 5px #ccc;margin-bottom: 10px;">
            <div class="col-lg-2" style="padding-left: 0;">
                <a href="javascript:void(0)"><img src="/img/example.jpg" alt="" height="100px;" width="100%;"></a>
            </div>
            <div class="col-lg-10" style="padding: 10px 0 0 0;height: 100px;">
                <p><a href="{{ route('admin.material.show',$material->id) }}">{{ $material->title }}</a></p>
                <small>{{ $material->digest }}</small>
                <br>
                <small style="position: absolute;bottom:5px; color:#ccc">阅读: {{ $material->reading_quantity }}&nbsp; 评论: <?php echo sizeof($material->comments) ?></small>
                <small style="position: absolute;bottom:5px; right: 15px; color:#ccc">{{ $material->updated_at }}</small>
            </div>
        </div>
    @endforeach

    <div class="row" id="pages">
        {{ $materials->links() }}
    </div>
@endsection