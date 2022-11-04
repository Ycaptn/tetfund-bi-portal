@extends('layouts.app')

@section('app_css')
    <style type="text/css">
        /* Style the tab */
        .tab {
          overflow: hidden;
          border: 1px solid #ccc;
          background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
          background-color: inherit;
          float: left;
          border: none;
          outline: none;
          cursor: pointer;
          padding: 14px 16px;
          transition: 0.3s;
          font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
          background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
          background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
          display: none;
          padding: 6px 12px;
          border: 1px solid #ccc;
          border-top: none;
        }
    </style>
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

    @if($submissionRequest->status == 'not-submitted')
        <a data-toggle="tooltip" 
            title="Edit" 
            data-val='{{$submissionRequest->id}}' 
            href="{{route('tf-bi-portal.submissionRequests.edit', $submissionRequest->id)}}" 
            class="btn btn-sm btn-primary btn-edit-mdl-submissionRequest-modal" href="#">
            <i class="fa fa-pencil-square-o"></i> Edit
        </a>
    @endif

    {{-- @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.submission_requests.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">

            @include('tf-bi-portal::pages.submission_requests.modal')
            @if($submissionRequest->status == 'not-submitted')
                <div class="row container alert alert-warning">
                    <div class="col-md-9">
                        <i class="icon fa fa-warning"></i>
                        <strong>PRE-SUBMISSION NOTICE:</strong> 
                        <ul>
                            <li>This request has <strong>NOT</strong> been submitted.</li>

                            @if ($submissionRequest->get_all_attachements_count_aside_additional($submissionRequest->id, 'Additional Attachment') < count($checklist_items)) 
                                <li>Please attach the <strong>required documents</strong> before submitting your request.</li>
                            @endif 

                            @if (isset($fund_available) && $fund_available != $submissionRequest->amount_requested)
                                <li>Fund requested must be equal to the <strong>Allocated amount</strong>.</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <form method="POST" action="{{ route('tf-bi-portal.submissionRequests.processSubmissionRequestToTFPortal', ['id'=>$submissionRequest->id]) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="submission_request_id" value="{{ $submissionRequest->id }}">
                            <input type="hidden" name="checklist_items_count" value="{{ count($checklist_items) }}">
                            <button type="submit" class="btn btn-sm btn-danger pull-right">  
                                Submit this Request 
                            </button>                        
                        </form>
                    </div>
                </div>
            @else
                <div class="row container alert alert-success">
                    <div class="col-md-9">
                        <i class="icon fa fa-success"></i>
                        <strong>SUBMISSION COMPLETED:</strong> 
                        <ul>
                            <li>This request has been <strong>successfully submitted!</strong></li>
                            <li>The submission was completed on <strong>
                                {{ \Carbon\Carbon::parse($submissionRequest->tf_iterum_portal_response_at)->format('l jS F Y') }}
                            .</strong></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <span class="text-success pull-right"> 
                            <strong>
                                <span class="fa fa-check-square"></span>
                                Request Submitted 
                            </strong> 
                        </span>
                    </div>
                </div>
            @endif           
            <div class="row col-sm-12">
                {{-- details --}}
                @include('tf-bi-portal::pages.submission_requests.partials.submission_details')
                <div class="container col-sm-12"><hr>
                    <div class="tab">
                        <ul class="nav">
                            <li class="mt-3" style="margin-right: 3px;">
                                <a href="#attachments?attachments=attachments" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event,'attachments')" id="defaultOpen">
                                    Attachments
                                </a>                        
                            </li>
                            <li class="mt-3" style="margin-right: 3px;">                            
                                <a href="#astd_nominations?astd_nominations=astd_nominations" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'astd_nominations')">
                                    Final Nominations
                                </a>
                            </li>
                            <li class="mt-3" style="margin-right: 3px;">                                
                                <a href="#communications?communications=communications" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'communications')">
                                    Communications
                                </a>
                            </li>
                        </ul>
                    </div><hr>
                    <div id="attachments" class="tabcontent">
                        <h4>ATTACHMENTS</h4>
                        @include('tf-bi-portal::pages.submission_requests.partials.submission_attachments') 
                    </div>

                    <div id="astd_nominations" class="tabcontent">
                        <h4>FINAL NOMINATIONS DETAILS</h4>
                        @include('tf-bi-portal::pages.submission_requests.partials.submission_astd_nominations')
                    </div>

                    <div id="communications" class="tabcontent">
                        <h4>COMMUNICATIONS</h4>
                        
                    </div>                   
                </div>
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
    <script type="text/javascript">
        function openCity(evt, cityName) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablinks");
          for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
          }
          document.getElementById(cityName).style.display = "block";
          evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
@endpush