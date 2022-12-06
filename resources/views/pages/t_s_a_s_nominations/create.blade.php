@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Create TSAS Nomination
@stop

@section('page_title')
Create TSAS Nomination
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tetfund-tsas.tSASNominations.index') }}">
    <i class="bx bx-chevron-left"></i> Back to TSAS Nomination Dashboard
</a>
@stop

@section('page_title_buttons')
{{--
<a id="btn-new-mdl-tSASNomination-modal" class="btn btn-sm btn-primary btn-new-mdl-tSASNomination-modal">
    <i class="bx bx-book-add me-1"></i>New T S A S Nomination
</a>
--}}
@stop

@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-4">
            <div class="card-title d-flex align-items-center">
                <div>
                    <i class="bx bxs-user me-1 font-22 text-primary"></i>
                </div>
                <h5 class="mb-0 text-primary">TSAS Nomination Details</h5>
            </div>
            <hr />
            {!! Form::open(['route' => 'tetfund-tsas.tSASNominations.store','class'=>'form-horizontal']) !!}
            
                @include('tf-bi-portal::pages.t_s_a_s_nominations.fields')

                <div class="col-lg-offset-3 col-lg-9">
                    <hr />
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('tetfund-tsas.tSASNominations.index') }}" class="btn btn-default btn-warning">Cancel</a>
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