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
                    <strong>PRE-SUBMISSION NOTICE:</strong> 
                    <ul>
                        @if (true)
                            <li>This request has <strong>NOT</strong> been submitted.</li>
                        @endif

                        @if (true)
                            <li>Please attach the <strong>required documents</strong> before submitting your request.</li>
                        @endif

                        @if (true)
                            <li>Fund requested must be equal to the <strong>Allocated amount</strong>.</li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-3">
                    <form method="POST" action="{{ route('tf-bi-portal.submissionRequests.processSubmissionRequestToTFPortal', ['id'=>$submissionRequest->id]) }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-sm btn-danger pull-right">  
                            Submit this Request 
                        </button>                        
                    </form>
                </div>
            </div>            
            <div class="row col-sm-12">
                {{-- details --}}
                @include('tf-bi-portal::pages.submission_requests.partials.submission_details')
                <div class="container col-sm-12"><hr>
                    <div class="tab">
                        <ul class="nav">
                            <li>
                                <a href="#attachments?attachments=attachments" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event,'attachments')" id="defaultOpen">
                                    Attachments
                                </a>                        
                            </li>
                            <li>                            
                                <a href="#astd_nominations?astd_nominations=astd_nominations" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'astd_nominations')">
                                    ASTD Nominations
                                </a>
                            </li>
                            <li>                                
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
                        <h4>ASTD NOMINATIONS</h4>
                        
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