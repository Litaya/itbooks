@extends('layouts.frame')

@section('title', '图书详情 | '.$book->name)

@section('content')
    <style>
        .well-showcase {
            height: 90%;
        }
    </style>
        <style>
        label {
            margin-top: 10px;
        }
        p {
            font-size: 10px;
        }
        .form-spacing-top{
            margin-top: 18px;
        }
        </style>
        @if(Session::has('status'))
          <div class="alert alert-info">
           {{Session::get('status')}}
          </div>
        @endif
        @if(Session::has('info'))
          <div class="alert alert-info">
           {{Session::get('info')}}
          </div>
        @endif
        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
            <div class="col-xs-7">
            图书信息
            </div>
          <button class="btn btn-default col-xs-4 col-sm-2 col-sm-offset-2 col-lg-1" onclick="location.href='{{route("purchase.add_cart",$book->isbn)}}'">加入购物车</button>
        </div>
        </div>
        <div class="panel-body">
          <div class="col-xs-5">
              @if($book->img_upload)
              <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset($book->img_upload)}}"></img>
              @else
              <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset('test_images/404.jpg')}}"></img>
              @endif

              @if(Auth::check())
              <div class="row">
              <div class="col-xs-12">
              <span class="pull-right">
                  @if(!empty($userlike) and $userlike)
                  <button id="like-button" class="btn btn-xs btn-default" style="color: #F77">
                  <i id="like-icon" class="fa fa-heart" aria-hidden="true"></i>&nbsp;想读</button>
                  @else
                  <button id="like-button" class="btn btn-xs btn-default">
                  <i id="like-icon" class="fa fa-heart-o" aria-hidden="true"></i>&nbsp;想读</button>
                  @endif <!-- END LIKE IF -->

                  @if(!empty($userread) and $userread)
                  <button id="read-button" class="btn btn-xs btn-default" style="color: #F77" onclick="unread()">
                  <i class="fa fa-history" aria-hidden="true"></i>&nbsp;读过</button>
                  @else
                  <button id="read-button" class="btn btn-xs btn-default" onclick="read()">
                  <i class="fa fa-history" aria-hidden="true"></i>&nbsp;读过</button>
                  @endif <!-- END READ IF -->
              </span>
              </div>
              </div>
              @endif

          </div>
          <div class="col-xs-7">
              <p><strong>{{$book->name}}</strong></p>
              <p>作者: {{$book->authors}}</p>
              <p>ISBN号: {{$book->isbn}}</p>
              <p>定价: {{number_format($book->price,2)}}</p>
              <p>类别: {{$book->type==0?"其他图书":($book->type==1?"教材":"非教材")}}</p>
              <p>出版时间: {{date('Y-m-d',strtotime($book->publish_time))}}</p>
              @if(Auth::check())
                  <button class="btn btn-default btn-xs" style="width:60px" onclick="location.href='{{route("favorite.store",$book->id)}}'"> 收藏</button>
                  @if($book->file_upload)
                  <a href="<?php echo "/pdfjs/web/viewer.html?file={$book->file_upload}".'.pdf';?>"><button class="btn btn-default btn-xs">试读图书</button></a>
                  @else
                  <a href="{{route('notFound')}}"><button class="btn btn-default btn-xs">试读图书</button></a>
                  @endif
              @endif
              <hr>
              @if(Auth::check())
              <p>课件:
                  @if(!empty($book->kj_url))
                  {{--<a href="{{$book->kj_url}}">下载课件</a>--}}
                      <a id="downloadcw" href="javascript:void(0)" onclick="downloadCourseware({{ $book->id }})">下载课件</a>
                  @endif
                  &nbsp;&nbsp;
                  <a href="javascript:updateKjUrl();">扫描课件变更</a></p>
              @endif
              <!-- if the book is open to reservations, and the user has enough privilege -->
              @if(Auth::check())
              <a href="{{route('bookreq.record')}}"><button class="btn btn-primary btn-xs">申请样书</button></a>
              @else
              <a href="https://itbook.kuaizhan.com/39/60/p332015340738c5"><button class="btn btn-primary btn-xs">申请样书</button></a>
              @endif
              <!-- end if -->
              <a href="{{route('home')}}"><button class="btn btn-default btn-xs">返回首页</button></a>
          </div>
       </div>
     </div>
     <div class="panel panel-default">
     <div class="panel-body">
       <div class="row">
      <a href="{{route('comment.show',$book->id)}}"> <button class="col-xs-4 col-xs-push-4 btn btn-primary" style="height:50px">相关评论</button></a>
      </div>
    </div>
  </div>
  @if(!empty($similar_books))
      <div class="panel panel-default">
          <div class="panel-heading">
          <div class="row">
              <div class="col-md-7">
              相似图书
              </div>
          </div>
          </div>


          <div class="panel-body">
              <div class="list-group">
              @foreach($similar_books as $s_book)
                  <a class="list-group-item" href="{{route('book.show', $s_book->id)}}">
                      @if(preg_match("/^[A-Za-z0-9]+/", $s_book->name))
                          {{$s_book->name}}
                      @else
                          {{mb_strlen($s_book->name) >= 18 ? mb_substr($s_book->name, 0, 15)."..." : $s_book->name }}
                      @endif
                  </a>
              @endforeach
              </div>
          </div>
        </div>
  @endif
  <div>
    <div class="bshare-custom">
      <a title="分享到" href="http://www.bShare.cn/" id="bshare-shareto" class="bshare-more">&nbsp;&nbsp;&nbsp;分享到</a>
      <a title="分享到QQ空间" class="bshare-qzone">QQ空间</a>
      <a title="分享到新浪微博" class="bshare-sinaminiblog">新浪微博</a>
      <a title="分享到人人网" class="bshare-renren">人人网</a>
      <a title="分享到腾讯微博" class="bshare-qqmb">腾讯微博</a>
    </div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
  </div>
<script>
function updateKjUrl(){
    var xmlhttp;
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            location.reload();
    };
    xmlhttp.open("GET", "{{route('book.updatekj', $book->id)}}", true);
    xmlhttp.send();
}

$(document).ready(function(){
    if( {{ Auth::check() ? 1 : 0}} ){
        if( {{empty($userlike) ? 1 : 0}} )
            $('#like-button').attr("onclick", "like()");
        else
            $('#like-button').attr("onclick", "unlike()");
    }

});

function like(){
    response = $.ajax({
        url : '{{route('like', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#like-button').attr("onclick", "unlike()");
            $('#like-button').css({"color": "#F77"});
            $('#like-icon').attr("class", "fa fa-heart");
        }
    });
}

function unlike(){
    response = $.ajax({
        url : '{{route('unlike', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#like-button').attr("onclick", "like()");
            $('#like-button').css({"color": "#777"});
            $('#like-icon').attr("class", "fa fa-heart-o");
        }
    });

}

function read(){
    response = $.ajax({
        url : '{{route('read', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#read-button').attr("onclick", "unread()");
            $('#read-button').css({"color": "#F77"});
        },
    });
}

function unread() {
    response = $.ajax({
        url : '{{route('unread', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#read-button').attr("onclick", "read()");
            $('#read-button').css({"color": "#777"});
        },
    });
}

function downloadCourseware(book_id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = "{{ route('book.downloadcw') }}";
    $.ajax({
        method:'post',
        url:url,
        data: {
            _token: CSRF_TOKEN,
            book_id: book_id
        },
        success:function () {
            $("#downloadcw").attr('href',"javascript:void(0)").removeAttr("onclick").css('color','#999').html("已将课件地址、解压密码发送到公众号聊天窗口<br/>");
        },
        error:function () {
            $("#downloadcw").html("下载失败，点击重试<br/>");
        }
    });
}
</script>


@endsection
