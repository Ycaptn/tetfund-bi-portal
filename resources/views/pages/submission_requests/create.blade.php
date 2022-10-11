@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
New Intervention Submission
@stop

@section('page_title')
Intervention
@stop

@section('page_title_suffix')
New Submission
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.submissionRequests.index') }}">
    <i class="bx bx-chevron-left"></i> Back to Submissions
</a>
@stop

@section('page_title_buttons')
{{--
<a id="btn-new-mdl-submissionRequest-modal" class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal">
    <i class="bx bx-book-add me-1"></i>New Submission Request
</a>
--}}
@stop

@section('content')
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body p-4">
            <div class="card-title d-flex align-items-center">
                <div>
                    <i class="bx bx-highlight me-1 font-22 text-primary"></i>
                </div>
                <h5 class="mb-0 text-primary">Please provide details of your submission</h5>
            </div>
            <hr />
            {!! Form::open(['route' => 'tf-bi-portal.submissionRequests.store','class'=>'form-horizontal']) !!}
            
                @include('pages.submission_requests.fields')

                <hr />

                <div class="col-lg-offset-3 col-lg-12">
                    {!! Form::submit('Submit Request', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-default btn-warning">Cancel Submission</a>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Submissions</h5></div>
        <p class="small">
            Process and track your submissions to TETFund from this window. You may begin new submissions by clicking the new submission button.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush