@extends('layouts.frame')

@section('title', '样书申请')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            {{ Form::text('search',null,['class'=>'form-control dropdown-toggle','placeholder'=>'请输入ISBN、书名、作者检索书籍','id'=>"search_box","data-toggle"=>"dropdown"]) }}
            <ul class="dropdown-menu" style="position: absolute;left: 20px;top: 40px; width: 90%" id="result_place">
            </ul>
            <p><small>您还可申请10本书</small></p>
        </div>
        <div class="col-xs-12">
            <form action="{{ route("bookreq.store.multiple") }}" method="post">
                {{ csrf_field() }}
                <h4><i class="fa fa-book"></i> 您选择的书籍</h4>
                <p><small>您可在个人中心上传相关书籍的学校订书单,审核通过后本次申请不扣总的申请次数</small></p>
                <ul class="list-group" id="selected_books">
                </ul>
                <h4> <i class="fa fa-map-marker"></i> 地址信息</h4>
                <div class="form-group">
                    <label for="receiver">收件人姓名</label>
                    <input type="tel" class="form-control" name="receiver" id="receiver" placeholder="收件人姓名">
                </div>
                <div class="form-group">
                    <label for="address">收件地址</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="请填写详细地址方便寄送">
                </div>
                <div class="form-group">
                    <label for="phone">联系电话</label>
                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="电话">
                </div>
                <div class="form-group">
                    <label for="phone">申请理由</label>
                    <textarea class="form-control" name="message" id="message"> </textarea>
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

                    $("#result_place").append("<li><a href='javascript:void(0)' class='book_item' id='book_"+book_id
                            +"' onclick='book_select(\""+book_id+"\",\""+book_com_name+"\")'>"+book_name+"</a></li>");
                }

            });
        });
        $("#search_box").trigger("input");

        function book_select(book_id,book_name){
            books_num ++;

            var icon_delete = "<i class='fa fa-times' style='color:red' onclick='remove_selected("+book_id+")'/>&nbsp;&nbsp;";
            var radio_boxes = "<label class='radio-inline'><input type='radio' name='typeOf"+book_id+"' value=1 checked='checked' required> 教材</label>";
            radio_boxes += "<label class='radio-inline'><input type='radio' name='typeOf"+book_id+"' value=2 required> 教辅</label> <br/>";
            var check_boxes = "<input type='checkbox' id='book_ids_"+book_id+"' name='book-ids[]' value="+book_id+" checked='checked'>";

            $("#selected_books").append("<li class='list-group-item' id='selected_"+book_id+"'>" + icon_delete
                    +radio_boxes+books_num+".&nbsp;"+book_name+" </li>");
            $("#checkboxes").append(check_boxes);
        }

        function remove_selected(book_id){
            books_num -- ;
            $("#selected_"+book_id).remove();
            $("#book_ids_"+book_id).remove();
        }
    </script>
@stop