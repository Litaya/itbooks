@extends('layouts.frame')

@section('title',"书圈-".$category->name)
@section('sub-title','-'.$category->name)
@section('content')

    <style>
        .item{
            box-shadow: 1px 1px 1px #ccc;
            background-color: white;
            padding: 0;
            margin-bottom: 10px;
        }
        .item-img{
            width:100%;
            min-height:80px;
        }
        .item-content{
            min-height: 80px;
            padding: 0 5px 0 10px;
        }
        .item-title{
            margin: 0;
        }
        .item-hint{
            color: #cccccc;;
        }
    </style>
    <form action="{{ route('material.index') }}" class="form hidden" method="get" >
        <input type="text" class="form-control" name="search" placeholder="您对什么感兴趣？">
    </form>

    <div class="row" id="materials" style="padding:20px;">
        @if(!empty($materials))
            @foreach($materials as $material)
                @if(!empty($material))
                    <a href="{{ $material['display']==1?route("material.show",$material['id']):$material['url'] }}">
                        <div class="col-xs-12 item">
                            <div class="col-xs-3" style="padding: 0;">
                                <img class="item-img" src="{{ $material['cover_path'] }}" alt="">
                            </div>
                            <div class="col-xs-9 item-content">
                                <p class="item-title">{{ \Illuminate\Support\Str::limit($material['title'],30) }}</p>
                                <small class="item-hint" style="position: absolute; bottom: 2px;">阅读 {{ $material['reading_quantity']  }}</small>
                                <small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">{{ $material['wechat_update_time']}}</small>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        @endif
    </div>
    <p id="load_more" style="text-align: center"><a href="javascript:void(0)" onclick="loadMaterials('{{ $category->id }}',2,20)">点击加载更多</a></p>
    <script>
        function loadMaterials(cate_id,page,per_page) {
            var url = "{{ route('api.material.catematerials') }}";
            $.ajax({
                method:'get',
                url:url,
                data: {
                    cate_id: cate_id,
                    page: page,
                    per_page: per_page
                },
                success:function (data) {
                    if(page == 1)
                        $("#cate"+cate_id+"_table").html("");
                    data = JSON.parse(data);
                    var length = data.length;
                    for(var i=0;i<length;i++){
                        var item_str = constructItem(data[i]);
                        $("#materials").append(item_str);
                    }
                    if(length == per_page)
                        $("#load_more").removeClass("hidden").html("<a href='javascript:void(0)' onclick='loadMaterials("+cate_id+","+(page+1)+","+per_page+")'>点击加载更多</a>");
                    else{
                        $("#load_more").html('没有更多啦')
                    }
                }
            });
        }
        function constructItem(material) {
            var href_url = material["display"]==1?"{{ route("material.show",$material['id']) }}":material["url"];
            var title    = material['title'].substr(0,30);
            return '<a href="'+href_url+'">'+
                '<div class="col-xs-12 item">'+
                '<div class="col-xs-3" style="padding: 0;"><img class="item-img" src="'+material['cover_path']+'" alt=""></div>'+
                '<div class="col-xs-9 item-content">'+
                '<p class="item-title">'+title+'</p>'+
                '<small class="item-hint" style="position: absolute; bottom: 2px;">阅读'+material['reading_quantity']+'</small>'+
                '<small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">'+material['wechat_update_time']+'</small>'+
                '</div></div></a>';
        }
    </script>
@endsection