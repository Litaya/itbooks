@if(isset($message))
    <div class="col-lg-12">
        <div class="panel panel-{{isset($status)?$status:'default'}}">
            <div class="panel-body">
                {{ $message }}
            </div>
        </div>
    </div>
@endif