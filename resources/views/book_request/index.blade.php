@extends('layouts.frame')

@section('title', '样书申请')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            {{ Form::text('search',null,['class'=>'form-control dropdown-toggle','placeholder'=>'输入ISBN、书名、作者检索','id'=>"search_box","data-toggle"=>"dropdown"]) }}
            <ul class="dropdown-menu" style="position: absolute;left: 20px;top: 40px; width: 90%" id="result_place">
            </ul>
            <p><small>您还可申请10本书</small></p>
        </div>
        <div class="col-xs-12">
            <hr>
            <form action="{{ route("bookreq.store.multiple") }}" method="post">
                <h4>您选择的书籍</h4>
                <p><small>您可在个人中心上传相关书籍的学校订书单,审核通过后本次申请不扣总的申请次数</small></p>
                <ul class="list-group" id="selected_books">
                </ul>
                <div class="form-group">
                    <label for="receiver">收件人</label>
                    <input type="tel" class="form-control" name="receiver" id="receiver" placeholder="收件人姓名">
                </div>
                <div class="form-group">
                    <label for="address">地址</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="请填写详细地址方便寄送">
                </div>
                <div class="form-group">
                    <label for="phone">电话</label>
                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="电话">
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

                    $("#result_place").append("<li><a href='javascript:void(0)' class='book_item' id='book_"+book_id
                            +"' onclick='book_select(\""+book_id+"\",\""+book_com_name+"\")'>"+book_name+"</a></li>");
                }

            });
        });
        $("#search_box").trigger("input");

        function book_select(book_id,book_name){
            books_num ++;

            var icon_delete = "<i class='fa fa-times' style='color:red' onclick='remove_selected("+book_id+")'/>&nbsp;&nbsp;";
            var radio_boxes = "<label class='radio-inline'><input type='radio' name='typeOf"+book_id+"' value='option1' required> 教材</label>";
            radio_boxes += "<label class='radio-inline'><input type='radio' name='typeOf"+book_id+"' value='option1' required> 教辅</label> <br/>";

            $("#selected_books").append("<li class='list-group-item' id='selected_"+book_id+"'>" + icon_delete
                    +radio_boxes+books_num+".&nbsp;"+book_name+" </li>");
        }

        function remove_selected(book_id){
            books_num -- ;
            $("#selected_"+book_id).remove();
        }
    </script>
@stop