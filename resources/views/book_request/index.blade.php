@extends('layouts.frame')

@section('title', '样书申请')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            {{ Form::text('search',null,['class'=>'form-control dropdown-toggle','placeholder'=>'请输入ISBN、书名、作者检索书籍','id'=>"search_box","data-toggle"=>"dropdown"]) }}
            <ul class="dropdown-menu" style="position: absolute;left: 20px;top: 40px; width: 90%" id="result_place">
            </ul>
            @if(strpos($user->certificate_as, "TEACHER") !== false)
            <p><small id="notice-book-limit" style="color:grey;font-size: 12px">您还可申请&nbsp;<span id="books_num" style="color:orange">{{ $user->json_content->teacher->book_limit }}</span>&nbsp;本书</small></p>
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
                    <label for="address">收件地址</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="请填写详细地址方便寄送"
                           value="{{ isset($userinfo->address)?$userinfo->address:"" }}">
                </div>
                <div class="form-group">
                    <label for="phone">联系电话</label>
                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="电话"
                           value="{{ isset($userinfo->phone)?$userinfo->phone:"" }}">
                </div>
                <div class="form-group">
                    <label for="book_plan">图书编写计划(可选)</label>
                    <textarea class="form-control" name="book_plan" id="book_plan" placeholder="近期是否有图书编写计划，书名是什么？"></textarea>
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

    <script>
        var books_num = 0;
        $("#search_box").on('input',function () {
            var search_string = $("#search_box").val();
            $.ajax({
                'url': "/api/book/search/"+search_string,
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
        });
        $("#search_box").trigger("input");

        function book_select(book_id,book_name,book_isbn){
            books_num ++;
            changeBookNum(-1);
            var icon_delete = "<i class='fa fa-times' style='color:red' onclick='remove_selected("+book_id+")'/>&nbsp;&nbsp;";
            var radio_boxes = "<label class='radio-inline'><input type='radio' name='typeOf"+book_id+"' value=1 checked='checked' required> 教材</label>";
            radio_boxes += "<label class='radio-inline'><input type='radio' name='typeOf"+book_id+"' value=2 required> 教辅</label> <br/>";
            var check_boxes = "<input type='checkbox' id='book_ids_"+book_id+"' name='book-ids[]' value="+book_id+" checked='checked'>";
            var p_book_isbn = "<small style='color:grey'>ISBN号："+book_isbn+"</small>";

            $("#selected_books").append("<li class='list-group-item' id='selected_"+book_id+"'>" + icon_delete
                    +radio_boxes+book_name+"<br/> "+p_book_isbn+"</li>");
            $("#checkboxes").append(check_boxes);
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
    </script>
@stop