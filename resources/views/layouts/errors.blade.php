<section class="small">

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible ml-10 mr-10">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>
        <h4><i class="icon fa fa-warning"></i> Errors!</h4>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block ml-10 mr-10">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ $message }}</strong>
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block ml-10 mr-10">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif


    @if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block ml-10 mr-10">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ $message }}</strong>
    </div>
    @endif


    @if ($message = Session::get('info'))
    <div class="alert alert-info alert-block ml-10 mr-10">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ $message }}</strong>
    </div>
    @endif


</section>
