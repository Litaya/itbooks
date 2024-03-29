@extends('admin.layouts.frame')
@section('title', '图书管理')
@section('content')

<div class="container">
    <div class="row">
    <div class="col-md-12">
        <div class="row">
        
            <!-- SEARCH BAR -->
            <div class="col-md-8"> 
            {!! Form::open(["route"=>"admin.book.index", "method"=>"GET"]) !!}
            {{ Form::text("search", null, ["placeholder"=>"ISBN、书名、作者..."]) }}
            {{ Form::submit("搜索") }}
            {!! Form::close() !!}
            </div>
            <!-- END SEARCH BAR -->

            <div class="col-md-2 pull-right">
            <a href="{{route('admin.book.create')}}"><button class="btn btn-default push-left">创建新书</button></a>
            <button class="btn btn-default push-left" data-toggle="modal" data-target="#import-modal">批量导入</button>
            </div>
        </div>
        
        <div class="row">
        <table class="table" style="column-width: 10px">
        <thead>
            <th onclick="orderBy('id')">
            <label class="unselectable" id="id-label">ID</label>
            @if(Request::get("orderby", "") == 'id')
            <i class="{{Input::get('asc')=='true'?'fa fa-caret-up':'fa fa-caret-down'}}": aria-hidden="true"></i>
            @endif
            </th>

            <th onclick="orderBy('name')">
            <label class="unselectable" id="name-label">书名</label>
            @if(Request::get("orderby", "") == 'name')
            <i class="{{Input::get('asc')=='true'?'fa fa-caret-up':'fa fa-caret-down'}}": aria-hidden="true"></i>
            @endif
            </th>

            <th onclick="orderBy('authors')">
            <label class="unselectable" id="authors-label">作者</label>
            @if(Request::get("orderby", "") == 'authors')
            <i class="{{Input::get('asc')=='true'?'fa fa-caret-up':'fa fa-caret-down'}}": aria-hidden="true"></i>
            @endif
            </th>


            <th onclick="orderBy('isbn')">
            <label class="unselectable" id="isbn-label">ISBN</label>
            @if(Request::get("orderby", "") == 'isbn')
            <i class="{{Input::get('asc')=='true'?'fa fa-caret-up':'fa fa-caret-down'}}": aria-hidden="true"></i>
            @endif
            </th>


            <th onclick="orderBy('type')">
            <label class="unselectable" id="type-label">分类</label>
            @if(Request::get("orderby", "") == 'type')
            <i class="{{Input::get('asc')=='true'?'fa fa-caret-up':'fa fa-caret-down'}}": aria-hidden="true"></i>
            @endif
            </th>

            <th></th>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr>
            <td>{{$book->id}}</td>
            <td width="30%"><a href="{{route("admin.book.show", $book->id)}}">{{$book->name}}</a></td>
            <td width="30%">{{$book->authors}}</td>
            <td>{{$book->isbn}}</td>
            <td>{{$book->type==0?"其他":($book->type==1?"教材":"非教材")}}</td>
            <td>
                <a href="{{route('admin.book.show', $book->id)}}"><button class="btn btn-xs btn-primary">详情</button></a>
                <a href="{{route('admin.book.edit', $book->id)}}"><button class="btn btn-xs btn-primary">编辑</button></a>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div>
            {!! $books->appends(Input::except('page'))->links() !!}
        </div>
        </div>  
    </div>
    </div>
</div>


<div class="modal fade" id="import-modal"
tabindex="-1" role="dialog"
aria-labelledby="import-modal-label">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="import-label">批量导入图书条目</h4>
            </div>
            <div class="modal-body">

            {!! Form::open(["route"=>"admin.book.import", "method"=>"POST", "files"=>true]) !!}
            {{ Form::label("excel", "上传EXCEL文档") }}
            {{ Form::file("excel", ["class"=>"form-control"]) }}
            <br>
            {{ Form::submit("确定", ['class'=>'btn-primary btn-block'])}}
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script>

var input_keys = new Array();
var input_values = new Array();
var seg0 = "{{route('admin.book.index')}}";

var old_orderby = "{{Request::get("orderby", "")}}";
var old_asc = "{{Request::get("asc", "false")}}";

@foreach(Input::except(["page", "orderby", "asc"]) as $key => $value)
input_keys.push("{{$key}}");
input_values.push("{{$value}}");
@endforeach

function orderBy(col){
    var url = seg0;
    var i;
    for(i=0; i<input_keys.length;i++){
        var k = input_keys[i];
        var v = input_values[i];
        if(k == "page") continue;
        url += (i==0?"?":"&") + k + "=" + v;
    }
    url += (i==0?"?":"&") + "orderby="+col;
    if(col != old_orderby) url += "&asc=true";
    else url += "&asc=" + (old_asc=="true"?"false":"true");
    window.location.href = url;
}

</script>

@endsection