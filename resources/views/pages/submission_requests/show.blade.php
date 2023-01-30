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
    {{$submitted_request_data->title ?? $submissionRequest->title}} | 
    <b>({{ strtoupper(optional($submitted_request_data)->request_status == 'new' ? 'In-Progress' : $submissionRequest->status ) }})</b>
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.submissionRequests.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Submission Request List
</a>
@stop

@section('page_title_buttons')

    {{-- <a data-toggle="tooltip" 
        title="New" 
        href="{{ route('tf-bi-portal.submissionRequests.create') }}" 
        data-val='{{$submissionRequest->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal">
        <i class="fa fa-eye"></i> New
    </a>&nbsp; --}}

    @if($submissionRequest->status == 'not-submitted')
        <a data-toggle="tooltip" 
            title="Edit" 
            data-val='{{$submissionRequest->id}}' 
            href="{{route('tf-bi-portal.submissionRequests.edit', $submissionRequest->id)}}" 
            class="btn btn-sm btn-primary btn-edit-mdl-submissionRequest-modal">
            <i class="fa fa-pencil-square-o"></i> Edit Submission Request
        </a>

        @if (str_contains(strtolower(optional($intervention)->name), 'teaching practice'))
            &nbsp;
            <a data-toggle="tooltip" 
                title="Preview the last uploaded minute of meeting by TPNomination Committee"
                data-val='tp' 
                href="#" 
                class="btn btn-sm btn-danger btn-committee-last-minute-of-meeting-modal">
                <i class="fa fa-clock"></i> TPNomination Committee Last Minute of Meeting
            </a>&nbsp;
        @endif

        @if (str_contains(strtolower(optional($intervention)->name), 'conference attendance'))
            &nbsp;
            <a data-toggle="tooltip" 
                title="Preview the last uploaded minute of meeting by CANomination Committee"
                data-val='ca' 
                href="#" 
                class="btn btn-sm btn-danger btn-committee-last-minute-of-meeting-modal">
                <i class="fa fa-square"></i> CANomination Committee Last Minute of Meeting
            </a>&nbsp;
        @endif

        @if (str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship'))
            &nbsp;
            <a data-toggle="tooltip" 
                title="Preview the last uploaded minute of meeting by TSASNomination Committee"
                data-val='tsas' 
                href="#" 
                class="btn btn-sm btn-danger btn-committee-last-minute-of-meeting-modal">
                <i class="fa fa-square"></i> TSASNomination Committee Last Minute of Meeting
            </a>&nbsp;
        @endif
    @endif

@stop



@section('content')
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">

            @include('tf-bi-portal::pages.submission_requests.modal')
            @if($submissionRequest->status == 'not-submitted')
                <div class="row container alert alert-warning">
                    <div class="col-md-9">
                        <i class="icon fa fa-warning"></i>
                        <strong>PRE-SUBMISSION NOTICE:</strong> 
                        <ul>
                            <li>This request has <strong>NOT</strong> been submitted.</li>

                            @if ($submissionRequest->get_all_attachments_count_aside_additional($submissionRequest->id, 'Additional Attachment') < count($checklist_items)) 
                                <li>Please attach the <strong>required documents</strong> before submitting your request.</li>
                            @endif 

                            @if (isset($fund_available) && $fund_available != $submissionRequest->amount_requested && (!str_contains(strtolower(optional($intervention)->name), 'teaching practice') && !str_contains(strtolower(optional($intervention)->name), 'conference attendance') && !str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship')) )
           
                                {{-- error for requested fund mismatched to allocated fund for non-astd interventions --}}
                                <li>Fund requested must be equal to the 
                                    <strong>
                                        <a title="Preview allocation amount details" data-val='{{$submissionRequest->id}}' href="#" class="btn-show-submissionRequestAllocationAmount text-primary"> 
                                            <u>allocated amount</u>.
                                        </a>
                                    </strong>
                                </li>
                           
                            @elseif (isset($fund_available) && $submissionRequest->amount_requested > $fund_available && (str_contains(strtolower(optional($intervention)->name), 'teaching practice') || str_contains(strtolower(optional($intervention)->name), 'conference attendance') || str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship')) )
                                
                                {{-- error for requested fund mismatched to allocated fund for all ASTD interventions --}}
                                <li>Fund requested cannot be greater than 
                                    <strong>
                                        <a title="Preview allocation amount details" data-val='{{$submissionRequest->id}}' href="#" class="btn-show-submissionRequestAllocationAmount text-primary"> 
                                            <u>allocated amount</u>.
                                        </a>
                                    </strong>
                                </li>
                            @endif
                            
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <form method="POST" action="{{ route('tf-bi-portal.submissionRequests.processSubmissionRequestToTFPortal', ['id'=>$submissionRequest->id]) }}" onSubmit="onSubmitAction()" id="form_final_submission">
                            @csrf
                            <input type="hidden" name="submission_request_id" value="{{ $submissionRequest->id }}">
                            <input type="hidden" name="checklist_items_count" value="{{ count($checklist_items) }}">
                            <input type="hidden" name="intervention_name" value="{{ $intervention->name }}">
                            <input type="submit" class="btn btn-sm btn-danger pull-right" value="Submit This Request"> 
                        </form>
                    </div>
                </div>
            {{-- @else --}}
                {{-- <div class="row container alert alert-success">
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
                        <div class="col-sm-12">
                            <span class="text-success pull-right"> 
                                <strong>
                                    <span class="fa fa-check-square"></span>
                                    Request Submitted
                                </strong> 
                            </span>
                        </div>
                        <div class="col-sm-12 text-danger text-justify">
                            Please note that your Approval-In-Principle (AIP) is in the final stage of approval and you will be contacted for collection.
                        </div>
                    </div>
                    
                </div> --}}
            @endif           
            <div class="row col-sm-12">
                
                {{-- details and allocation preview modal --}}
                @include('tf-bi-portal::pages.submission_requests.partials.submission_details')

                {{-- sub menu buttons --}}
                @if(strtolower($submissionRequest->status) == 'submitted' || str_contains(strtolower(optional($intervention)->name), 'teaching practice') || str_contains(strtolower(optional($intervention)->name), 'conference attendance') || str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship'))
                    <div class="container col-sm-12"><hr>
                        <div class="tab">
                            <ul class="nav">
                                <li class="mt-3" style="margin-right: 3px;">
                                    <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?sub_menu_items=attachments" class="tablinks btn btn-primary btn-md shadow-none">
                                        Attachments
                                    </a>                        
                                </li>

                                @if(str_contains(strtolower(optional($intervention)->name), 'teaching practice') || str_contains(strtolower(optional($intervention)->name), 'conference attendance') || str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship'))
                                    <li class="mt-3" style="margin-right: 3px;">                            
                                        <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?sub_menu_items=nominations_binded" class="tablinks btn btn-primary btn-md shadow-none">
                                            Nominations
                                        </a>
                                    </li>
                                @endif

                                @if(strtolower($submissionRequest->status) == 'submitted')
                                    <li class="mt-3" style="margin-right: 3px;">
                                        <a href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?sub_menu_items=communications" class="tablinks btn btn-primary btn-md shadow-none">
                                            Communications
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    <hr>

                    {{-- sub menu contents --}}
                    @if(isset(request()->sub_menu_items) && request()->sub_menu_items == 'nominations_binded')
                        <div id="astd_nominations" class="col-sm-12 table-responsive">
                            <h4>NOMINATIONS LIST</h4>
                            @include('tf-bi-portal::pages.submission_requests.partials.submission_nominations_table')
                        </div>
                    @elseif(isset(request()->sub_menu_items) && request()->sub_menu_items == 'communications')
                        <div id="communications" class="col-sm-12">
                            <h4>COMMUNICATIONS</h4>
                            @include('tf-bi-portal::pages.submission_requests.partials.submission_communications')
                        </div>    
                    @else
                        <div id="attachments" class="col-sm-12">
                            <h4>ATTACHMENTS</h4>
                            @include('tf-bi-portal::pages.submission_requests.partials.submission_attachments') 
                        </div>
                    @endif
                                   
                </div>
            </div>
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
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

        //Show Modal for Allocation Details Preview
        $(document).on('click', ".btn-show-submissionRequestAllocationAmount", function(e) {
            $('#mdl-submissionRequestAllocationAmount-modal').modal('show');
        });

        function onSubmitAction() {
            event.preventDefault();
            swal({
                title: "Validate & Process This Submission Request?",
                text: "Pls do confirm completion of this submission by clicking 'Yes submit'.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes submit",
                cancelButtonText: "No don't submit",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                   $("#form_final_submission").submit();
                   swal({
                        title: '<div id="spinner-final-submission" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                        text: "Validating Requirements And Completing Submission! <br><br> Do not refresh this page! ",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });
                   return true;
                } else {
                    return false;
                }
            });
        }
    </script>
@endpush