@extends('layouts.app')

@php
    $redired_to_details_page_on_edit_from_card_list = true;
@endphp

@section('app_css')
    {!! $cdv_submission_requests->render_css() !!}
@stop

@section('title_postfix')
TETFund
@stop

@section('page_title')
TETFund
@stop

@section('page_title_suffix')
Monitoring
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
{{-- <a id="btn-new-mdl-submissionRequest-modal" class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal" href="{{ route("tf-bi-portal.submissionRequests.create") }}">
    <i class="bx bx-book-add mr-1"></i> New Monitoring
</a> --}}
@stop

@section('content')
    <div class="card border-top border-4 border-success">
        <div class="card-body">
            {{ $cdv_submission_requests->render() }}
            @include('tf-bi-portal::pages.monitoring.modal')
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Monitoring</h5></div>
        <p class="small">
            Process and track all your monitoring request to TETFund from this window.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_submission_requests->render_js() !!}
@endpush