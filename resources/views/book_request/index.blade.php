@extends('layouts.frame')

@section('title', '样书申请')

@section('content')

    <style>
        .item{
            box-shadow: 1px 1px 1px #eee;
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

    <div class="row">
        <div class="panel panel-danger hidden" id="warning_box">
            <div class="panel-body" id="warning_msg">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            {{ Form::text('search',null,['class'=>'form-control dropdown-toggle','placeholder'=>'请输入ISBN、书名、作者检索书籍','id'=>"search_box","data-toggle"=>"dropdown"]) }}
            <ul class="dropdown-menu" style="position: absolute;left: 20px;top: 40px; width: 90%" id="result_place">
            </ul>
            @if(strpos($user->certificate_as, "TEACHER") !== false)
                <p><small id="notice-book-limit" style="color:grey;font-size: 12px">您还可申请&nbsp;<span id="books_num" style="color:orange">{{ $user->json_content->teacher->book_limit }}</span>&nbsp;本书（教师每年最多只能申请10本样书）</small></p>
            @elseif(strpos($user->certificate_as, "AUTHOR") !== false)
                <p><small id="notice-book-limit" style="color:grey;font-size: 12px">您还可申请&nbsp;<span id="books_num" style="color:orange">{{ $user->json_content->author->book_limit }}</span>&nbsp;本书</small></p>
            @endif
        </div>
        <div class="col-xs-12">
            <form action="{{ route("bookreq.store.multiple") }}" method="post">
                {{ csrf_field() }}
                <h4><i class="fa fa-book"></i> 您选择的书籍 <small>在搜索框搜索选择</small></h4>
                <ul class="list-group" id="selected_books">
                </ul>
                <h4> <i class="fa fa-map-marker"></i> 地址信息</h4>
                <div class="form-group">
                    <label for="receiver">收件人姓名</label>
                    <input type="text" class="form-control" name="receiver" id="receiver" placeholder="收件人姓名"
                           value="{{ isset($userinfo->realname)?$userinfo->realname:"" }}">
                </div>
                <div class="form-group">
                    <label for="address" >收件地址（<span style="color:red">写清：省、市、区、路单位名称或住址）</span></label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="请填写详细地址方便寄送"
                           value="{{ isset($userinfo->address)?$userinfo->address:"" }}">
                </div>
                <div class="form-group">
                    <label for="phone">联系电话</label>
                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="电话"
                           value="{{ isset($userinfo->phone)?$userinfo->phone:"" }}">
                </div>
                <div class="form-group">
                    <label for="book_plan">目前教材使用情况(可选)</label>
                    <textarea class="form-control" name="book_plan" id="book_plan" placeholder="使用教材的书名、作者、出版社"></textarea>
                </div>
                <div class="form-group">
                    <label for="remarks">备注(可选)</label>
                    <textarea class="form-control" name="remarks" id="remarks" placeholder="备注"></textarea>
                </div>
                <div class="form-group" id="checkboxes"  hidden>
                </div>
                <button type="submit" class="btn btn-default">提交</button>
            </form>
        </div>
    </div>

    <!-- TODO 这里太暴力，写死了，要改 -->
    @if(!empty($banner_items))
        <div class="row">
            <hr>
            <div class="col-lg-12">
                @foreach($banner_items as $material)
                    <a href="{{ $material->display==1?route("material.show",$material->id):$material->url }}">
                        {{--<a href="{{ $material->url }}">--}}
                        <div class="col-xs-12 item">
                            <div class="col-xs-3" style="padding: 0;">
                                <img class="item-img" src="{{ $material->cover_path }}" alt="">
                            </div>
                            <div class="col-xs-9 item-content">
                                <p class="item-title">{{ \Illuminate\Support\Str::limit($material->title,50) }}</p>
                                <small class="item-hint" style="position: absolute; bottom: 2px;">阅读 {{ $material->reading_quantity  }}</small>
                                <small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">{{ $material->wechat_update_time }}</small>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <script>
        var books_num = 0;
        var search_timer = null;

        $("#search_box").on('input', function () {
            if(search_timer != null) clearTimeout(search_timer);
            search_timer = setTimeout(search, 500);
        });
        $("#search_box").trigger("input");

        function book_select(book_id,book_name,book_isbn){
            books_num ++;
            if(checkNum(-1)){
                changeBookNum(-1);
                var icon_delete = "<i class='fa fa-times' style='color:red' onclick='remove_selected("+book_id+")'/>&nbsp;&nbsp;";
                var check_boxes = "<input type='checkbox' id='book_ids_"+book_id+"' name='book-ids[]' value="+book_id+" checked='checked'>";
                var p_book_isbn = "<small style='color:grey'>ISBN号："+book_isbn+"</small>";
                $("#selected_books").append("<li class='list-group-item' id='selected_"+book_id+"'>" + icon_delete + book_name+"<br/> "+p_book_isbn+"</li>");
                $("#checkboxes").append(check_boxes);

                $("#warning_box").addClass("hidden");
                $("#warning_msg").html("");

            }else{
                $("#warning_box").removeClass("hidden");
                $("#warning_msg").html("您的申请额度已经用完！");
            }

        }

        function search(){
            var search_string = $("#search_box").val();
            $.ajax({
                'url': "/api/book/search_teaching/"+search_string,
                'method': 'post'
            }).done(function(result){

                var books = JSON.parse(result);
                books = books['data'];
                $("#result_place").html("");
                for(var i in books){

                    var book_com_name = books[i]["name"];
                    var book_name = books[i]["name"].length>=20?books[i]["name"].substring(0,20)+"...":books[i]["name"];
                    var book_id   = books[i]['id'];
                    var book_isbn = books[i]['isbn'];

                    $("#result_place").append("<li><a href='javascript:void(0)' class='book_item' id='book_"+book_id
                        +"' onclick='book_select(\""+book_id+"\",\""+book_com_name+"\",\""+book_isbn+"\")'>"+book_name+"</a></li>");
                }

            });
        }

        function remove_selected(book_id){
            books_num -- ;
            changeBookNum(1);
            $("#selected_"+book_id).remove();
            $("#book_ids_"+book_id).remove();
        }

        function changeBookNum(x){
            var i = parseInt($("#books_num").html());
            i = i+x;
            $("#books_num").html(i);
        }

        function checkNum(x){
            var i = parseInt($("#books_num").html());
            if(i+x < 0) return false;
            return true;
        }
    </script>

    @if(!empty($addbook))
        <script>
            book_select("{{$addbook->id}}", "{{$addbook->name}}", "{{$addbook->isbn}}");
        </script>
    @endif

@stop
