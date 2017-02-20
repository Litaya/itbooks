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
                @foreach($user->bookRequests as $bookreq)
                    <tr>
                        <td>{{$bookreq->book_id}}</th>
                        <td>{{$bookreq->status}}</th>
                        <td>{{$bookreq->message}}</th>
                        <td><a href="show">Show Detail</a></th>
                    </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection