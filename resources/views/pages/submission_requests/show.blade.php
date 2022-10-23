@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Submission Request
@stop

@section('page_title')
Submission Request
@stop

@section('page_title_suffix')
{{$submissionRequest->title}} | <b>({{ strtoupper($submissionRequest->status) }})</b>
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.submissionRequests.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Submission Request List
</a>
@stop

@section('page_title_buttons')

    <a data-toggle="tooltip" 
        title="New" 
        href="{{ route('tf-bi-portal.submissionRequests.create') }}" 
        data-val='{{$submissionRequest->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal" href="#">
        <i class="fa fa-eye"></i> New
    </a>&nbsp;

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$submissionRequest->id}}' 
        href="{{route('tf-bi-portal.submissionRequests.edit', $submissionRequest->id)}}" 
        class="btn btn-sm btn-primary btn-edit-mdl-submissionRequest-modal" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    {{-- @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.submission_requests.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">

            @include('tf-bi-portal::pages.submission_requests.modal')
            <div class="row alert alert-warning">
                <div class="col-md-9">
                    <i class="icon fa fa-warning"></i>
                    <strong>Notice:</strong> This request has <strong>NOT</strong> been submitted.
                    <ul>
                        @if (true)
                            @if(isset(request()->attach) && request()->attach == true)
                                
                                <li>You can <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}">preview</a> request details before submitting your request.</li>
                            @else
                                <li>Please <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?attach=true">attach</a> the <strong>required documents</strong> before submitting your request.</li>
                            @endif
                        @endif
                    </ul>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('tf-bi-portal.processSubmissionRequestToTFPortal') }}" class="btn btn-sm btn-danger pull-right">
                        Submit this Request
                    </a>
                </div>
            </div>            
            <div class="row col-sm-12">
                @if (isset(request()->attach) && request()->attach == true)
                    @include('tf-bi-portal::pages.submission_requests.partials.submission_attachments')
                @else
                    @include('tf-bi-portal::pages.submission_requests.partials.submission_details')
                @endif
            </div>
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