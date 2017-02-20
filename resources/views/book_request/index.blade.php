@extends('layouts.frame')

@section('title', '样书申请')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th>Head1</th>
                        <th>Head2</th>
                        <th>Head3</th>
                    </tr>
                </thead>
                <tbody>
                @for($i=0;$i<10;$i++)
                    <tr>
                        <td>Name {{$i}}</th>
                        <td>Status {{$i}}</th>
                        <td><a href="show">Show Detail</a></th>
                    </tr>
                @endfor
                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection