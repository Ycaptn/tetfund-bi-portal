@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
TSAS Nomination
@stop

@section('page_title')
TSAS Nomination
@stop

@section('page_title_suffix')
TSAS Nomination Details {{-- {{$tSASNomination->title}} --}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to TSAS Nomination List
</a>
@stop

@section('page_title_buttons')

    <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$tSASNomination->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-tSASNomination-modal pt-2" href="#">
        <i class="fa fa-eye"></i> New
    </a>&nbsp;

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$tSASNomination->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-tSASNomination-modal pt-2" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    {{-- @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.t_s_a_s_nominations.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">

            @include('tf-bi-portal::pages.t_s_a_s_nominations.modal') 
            @include('tf-bi-portal::pages.t_s_a_s_nominations.show_fields')
            
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