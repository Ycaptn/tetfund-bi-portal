@extends('layouts.app')

@section('app_css')
    {!! $cdv_a_s_t_d_nominations->render_css() !!}
@stop

@section('title_postfix')
ASTD Nominations
@stop

@section('page_title')
ASTD Nominations
@stop

@section('page_title_suffix')
All ASTD Nominations
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
<a id="btn-new-mdl-aSTDNomination-modal" class="btn btn-sm btn-primary btn-new-mdl-aSTDNomination-modal pt-2">
    <i class="bx bx-book-add me-1"></i>New ASTD Nomination
</a>
{{-- @if (Auth()->user()->hasAnyRole(['','admin']))
    @include('tf-bi-portal::pages.a_s_t_d_nominations.bulk-upload-modal')
@endif --}}
@stop

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 hidden-sm hidden-xs">
        {{-- Summary Row --}}
    </div>
    
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            {{ $cdv_a_s_t_d_nominations->render() }}
        </div>
    </div>

    @include('tf-bi-portal::pages.a_s_t_d_nominations.modal')
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">ASTD Nominations</h5></div>
        <p class="small">
            Staff from your institutions may request to be nominated for ASTD programs. Use this window to select staff from your institutions that will partipate in ASTD projects.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_a_s_t_d_nominations->render_js() !!}
@endpush