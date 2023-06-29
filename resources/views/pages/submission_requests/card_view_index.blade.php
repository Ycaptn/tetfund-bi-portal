@extends('layouts.app')

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
Submissions
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
    @if($current_user->hasRole('BI-desk-officer'))
        <a id="btn-new-mdl-submissionRequest-modal" class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal" href="{{ route("tf-bi-portal.submissionRequests.create") }}">
            <i class="bx bx-book-add mr-1"></i> New Submission
        </a>
    @endif
@stop

@section('content')
    <div class="card border-top border-4 border-success">
        <div class="card-body">
            {{ $cdv_submission_requests->render() }}

            @include('tf-bi-portal::pages.submission_requests.modal')
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Submissions</h5></div>
        <p class="small text-justify">
            Process and track your submissions to TETFund from this window. You may begin new submissions by clicking the new submission button.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_submission_requests->render_js() !!}
@endpush