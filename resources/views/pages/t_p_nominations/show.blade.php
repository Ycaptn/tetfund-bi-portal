@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
TP Nomination
@stop

@section('page_title')
TP Nomination
@stop

@section('page_title_suffix')
TP Nomination Details {{-- {{$tPNomination->title}} --}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.t_p_nominations.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to TP Nomination List
</a>
@stop

@section('page_title_buttons')

    <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$tPNomination->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-tPNomination-modal pt-2" href="#">
        <i class="fa fa-eye"></i> New
    </a>&nbsp;

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$tPNomination->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-tPNomination-modal pt-2" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    {{-- @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.t_p_nominations.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">

            @include('tf-bi-portal::pages.t_p_nominations.modal') 
            @include('tf-bi-portal::pages.t_p_nominations.show_fields')
            
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