@extends('layouts.frame')

@section('title', '样书申请')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th>BookId</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach(Auth::user()->bookRequests as $bookreq)
                    <form id="remove-form-{{$bookreq->id}}" action="{{ route('bookreq.destroy', $bookreq->id) }}" 
                        method="delete" style="display: none;">
                        <input type="hidden" name="method" value="DELETE">
                        {{ csrf_field() }}
                    </form>
                    <tr>
                        <td>{{$bookreq->book_id}}</td>
                        <td>{{$bookreq->status}}</td>
                        <td>{{$bookreq->message}}</td>
                        <td>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{route('bookreq.show', $bookreq->id)}}">详情</a>
                            </div>
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-md-6">
                                {!! Form::open(['route'=>['bookreq.destroy', $bookreq->id], 'method'=>'DELETE']) !!}
                                {!! Form::submit('Delete', ['class'=>'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS DELETE PERMISSION -->
                        </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection