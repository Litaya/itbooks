@extends('layouts.frame')

@section('title', '样书申请')

@section('content')
    <div class="container">
        <div class="row">
            {{--<p style="font-size: 12px; color:#ccc">Tips:&nbsp;您可在申请详情页上传相关书籍的学校订书单,审核通过后相关申请不扣总的申请次数</p>--}}
            <div class="panel panel-default">
                <div class="panel-body">
                    <small style="font-size: 12px; color:grey">亲爱的{{ Auth::user()->username }}，您好。您今年共申请了{{ sizeof(Auth::user()->bookRequests()->whereIn('status',[1,0])->get()) }}本样书，今年的总申请额度还有{{ json_decode(Auth::user()->json_content)->teacher->book_limit }}本。
                        <br>您可<a href="{{ route("bookreq.index") }}">点击此处</a>申请样书</small>
                </div>
            </div>
        </div>
        <div class="row">
            <h4>申请记录</h4>
            <div class="list-group">
                @foreach(Auth::user()->bookRequests as $bookreq)
                    <a href="{{ !empty($bookreq->book) ? route('bookreq.show', $bookreq->id) : '#' }}" class="list-group-item">
                        @if(!empty($bookreq->book))
                            <h5 class="list-group-item-heading">{{ $bookreq->book->name }}</h5>
                        @else
                            <h5 class="list-group-item-heading">[本社不再提供此书]</h5>
                        @endif
                        <small class="list-group-item-text">
                            {{ $bookreq->created_at }}&nbsp;
                            <span style="color:{{ $bookreq->status==0?"#7098DA":($bookreq->status==1?"green":"red") }}">
                                {{ $bookreq->status==0?"正在审核":($bookreq->status==1?"已通过":"未通过") }}
                            </span>
                        </small>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection