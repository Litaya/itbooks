@if(isset($message))
    <div class="panel panel-{{isset($status)?$status:'default'}}">
        <div class="panel-body">
            {{ $message }}
        </div>
    </div>
@endif