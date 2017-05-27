@extends('layouts.frame')


@section('title', '发表评价')
@section('content')
<div id="new">
  <form action="{{route('comment.store', $book->id)}}" method="POST">
      {!! csrf_field() !!}
      <div class="form-group">
        <label>Content</label>
            <textarea name="content" id="newFormContent" class="form-control" rows="10" required="required"></textarea>
        </div>
            <button type="submit" class="btn btn-lg btn-success col-lg-12">Submit</button>
    </form>
</div>

@endsection
