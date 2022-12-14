@extends('layouts.app')

@section('app_css')
    {!! $cdv_c_a_nominations->render_css() !!}
@stop

@section('title_postfix')
CA Nominations
@stop

@section('page_title')
CA Nominations
@stop

@section('page_title_suffix')
All CA Nominations
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
<a id="btn-new-mdl-cANomination-modal" class="btn btn-sm btn-primary btn-new-mdl-cANomination-modal pt-2">
    <i class="bx bx-book-add me-1"></i>New CA Nomination
</a>
{{-- @if (Auth()->user()->hasAnyRole(['','admin']))
    @include('tetfund-astd-module::pages.c_a_nominations.bulk-upload-modal')
@endif --}}
@stop

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 hidden-sm hidden-xs">
        {{-- Summary Row --}}
    </div>
    
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            {{ $cdv_c_a_nominations->render() }}
        </div>
    </div>

    @include('tetfund-astd-module::pages.c_a_nominations.modal')
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
    {!! $cdv_c_a_nominations->render_js() !!}
@endpush