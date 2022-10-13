@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
ASTD Nomination
@stop

@section('page_title')
ASTD Nomination
@stop

@section('page_title_suffix')
{{$aSTDNomination->title}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tetfund-astd.a_s_t_d_nominations.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to ASTD Nomination List
</a>
@stop

@section('page_title_buttons')

    <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$aSTDNomination->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-aSTDNomination-modal pt-2" href="#">
        <i class="fa fa-eye"></i> New
    </a>&nbsp;

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$aSTDNomination->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-aSTDNomination-modal pt-2" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    {{-- @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tetfund-astd-module::pages.a_s_t_d_nominations.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">

            @include('tetfund-astd-module::pages.a_s_t_d_nominations.modal') 
            @include('tetfund-astd-module::pages.a_s_t_d_nominations.show_fields')
            
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