@if(Session::has('notice_message'))
    <div class="col-lg-12">
        <div class="panel panel-{{Session::has('notice_status')?Session::get('notice_status'):'default'}}">
            <div class="panel-body">
                {{ Session::get('notice_message') }}
            </div>
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
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
    </ul>
    </div>
@endif

@if(Session::has('warning'))
    <div class="alert alert-warning" role="alert">
    <strong>Warning:</strong> {{Session::get('warning')}}
    </div>
@endif
