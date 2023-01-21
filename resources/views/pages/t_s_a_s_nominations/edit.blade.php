@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Edit T S A S Nomination
@stop

@section('page_title')
Edit T S A S Nomination
@stop

@section('page_title_suffix')
{{ $tSASNomination->id }}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tetfund-tsas.tSASNominations.show', $tSASNomination->id) }}">
    <i class="bx bx-chevron-left"></i> Back to T S A S Nomination Details
</a>
@stop

@section('page_title_buttons')
<a href="{{ route('tetfund-tsas.tSASNominations.create') }}" id="btn-new-tSASNominations" class="btn btn-sm btn-primary">
    <i class="bx bx-book-add mr-1"></i>New T S A S Nomination
</a>
@stop

@section('content')
<div class="card border-top border-0 border-4 border-success">
    <div class="card-body p-4">

        <div class="card-title d-flex align-items-center">
            <div>
                <i class="bx bxs-user me-1 font-22 text-primary"></i>
            </div>
            <h5 class="mb-0 text-primary">Modify T S A S Nomination Details</h5>
        </div>

        {!! Form::model($tSASNomination, ['class'=>'form-horizontal', 'route' => ['tetfund-tsas.tSASNominations.update', $tSASNomination->id], 'method' => 'patch']) !!}

            @include('tf-bi-portal::pages.t_s_a_s_nominations.fields')

            <div class="col-lg-offset-3 col-lg-9">
                <hr/>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('tetfund-tsas.tSASNominations.show', $tSASNomination->id) }}" class="btn btn-warning btn-default">Cancel</a>
            </div>
        {!! Form::close() !!}                

    </div>
</div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
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