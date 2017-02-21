@if(isset($message))
    <div class="panel panel-{{isset($status)?$status:'default'}}">
        <div class="panel-body">
            {{ $message }}
            <p></p>
            @if(isset($url))
                <a href="{{ url($url) }}">点击此处 >></a>
            @endif
        </div>
    </div>
@endif


@if(Session::has('success'))
    <div class="alert alert-success" role="alert">
        <p><strong>Success:</strong> {{Session::get('success')}}</p>
    </div>
@endif

@if(count($errors)>0)
    <div class="alert alert-danger" role="alert">
    <strong>Error:</strong>
    <ul>
    @foreach($errors as $error)
    <li>{{$error}}</li>
    @endforeach
    </ul>
    </div>
@endif
