@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Edit A S T D Nomination
@stop

@section('page_title')
Edit A S T D Nomination
@stop

@section('page_title_suffix')
{{ $aSTDNomination->id }}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tetfund-astd.aSTDNominations.show', $aSTDNomination->id) }}">
    <i class="bx bx-chevron-left"></i> Back to A S T D Nomination Details
</a>
@stop

@section('page_title_buttons')
<a href="{{ route('tetfund-astd.aSTDNominations.create') }}" id="btn-new-aSTDNominations" class="btn btn-sm btn-primary">
    <i class="bx bx-book-add mr-1"></i>New A S T D Nomination
</a>
@stop

@section('content')
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body p-4">

        <div class="card-title d-flex align-items-center">
            <div>
                <i class="bx bxs-user me-1 font-22 text-primary"></i>
            </div>
            <h5 class="mb-0 text-primary">Modify A S T D Nomination Details</h5>
        </div>

        {!! Form::model($aSTDNomination, ['class'=>'form-horizontal', 'route' => ['tetfund-astd.aSTDNominations.update', $aSTDNomination->id], 'method' => 'patch']) !!}

            @include('tetfund-astd-module::pages.a_s_t_d_nominations.fields')

            <div class="col-lg-offset-3 col-lg-9">
                <hr/>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('tetfund-astd.aSTDNominations.show', $aSTDNomination->id) }}" class="btn btn-warning btn-default">Cancel</a>
            </div>
        {!! Form::close() !!}                

    </div>
</div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            This is the help message.
            This is the help message.
            This is the help message.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush