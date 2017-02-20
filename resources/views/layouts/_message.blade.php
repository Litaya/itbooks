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