<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.basic')}}"><button type="button" class="btn {{ Request::is('*basic') ? 'btn-success':'btn-default'}}">基本信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.detail')}}"><button type="button" class="btn {{ Request::is('*detail') ? 'btn-success':'btn-default'}}">详细信息</button></a>
  </div>
  @if(Auth::user()->userinfo->role == "teacher")
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.teacher')}}"><button type="button" class="btn {{ Request::is('*teacher') ? 'btn-success':'btn-default'}}">教师附加信息</button></a>
  </div>
  @endif
  @if(Auth::user()->userinfo->role == "author")
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.author')}}"><button type="button" class="btn {{ Request::is('*author') ? 'btn-success':'btn-default'}}">作者附加信息</button></a>
  </div>
  @endif
</div>
<br>