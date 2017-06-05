@extends('layouts.frame')

@section('title', '发表评论')

@section('content')
<div id="new">
  <form action="{{route('comment.store', $book->id)}}" method="POST">
      {!! csrf_field() !!}
      <div class="form-group">
        <label>Content</label>
            <textarea name="content" id="newFormContent" class="form-control" rows="10" required="required" maxlength="512" placeholder="输入不能为空，最大512个字符"></textarea>
      </div>
            <button type="submit" class="btn btn-lg btn-success col-lg-12">Submit</button>
    </form>
</div>

@endsection
