@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Submission
@stop

@section('page_title')
Submission
@stop

@section('page_title_suffix')
    {{isset($submitted_request_data->title) && $submissionRequest->status=='submitted' ?
        $submitted_request_data->title : $submissionRequest->title }} - 
    @if(!empty($submitted_request_data) && ( ($submissionRequest->is_aip_request==true && $submitted_request_data->has_generated_aip==true) || ( ($submissionRequest->is_first_tranche_request==true || $submissionRequest->is_second_tranche_request==true || $submissionRequest->is_final_tranche_request==true) && $submitted_request_data->has_generated_disbursement_memo==true) ) )
        <b class="text-success">
            {{$submissionRequest->is_aip_request==true ? $submissionRequest->type : $submissionRequest->type.' Request' }} Processed
        </b>
    @else
        @if(optional($submitted_request_data)->request_status=='pending-recall')
            <b>(PENDING-RECALL)</b>
        @elseif(optional($submitted_request_data)->request_status=='recalled')
            <b>(RECALL-APPROVED)</b>
        @else
            <b>({{ strtoupper(optional($submitted_request_data)->request_status == 'new' ? 'In-Progress' : $submissionRequest->status ) }})</b>
        @endif
    @endif
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.submissionRequests.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Submission Request List
</a>
@stop

@section('page_title_buttons')
    @if($submissionRequest->status=='not-submitted' && ($submissionRequest->is_aip_request==true || ($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(optional($intervention)->name))))
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
                <i class="fa fa-clock"></i> CANomination Committee Last Minute of Meeting
            </a>&nbsp;
        @endif

        @if (str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship'))
            &nbsp;
            <a data-toggle="tooltip" 
                title="Preview the last uploaded minute of meeting by TSASNomination Committee"
                data-val='tsas' 
                href="#" 
                class="btn btn-sm btn-danger btn-committee-last-minute-of-meeting-modal">
                <i class="fa fa-clock"></i> TSASNomination Committee Last Minute of Meeting
            </a>&nbsp;
        @endif
    @endif

@stop



@section('content')
    <div class="card border-top border-4 border-success">
        <div class="card-body">

            @include('tf-bi-portal::pages.submission_requests.modal')
            @if($submissionRequest->status == 'not-submitted')
                <div class="row alert alert-warning">
                    <div class="col-md-9">
                        <i class="icon fa fa-warning"></i>
                        <strong>PRE-SUBMISSION NOTICE:</strong> 
                        <ul>
                            <li>This request has <strong>NOT</strong> been submitted.</li>
                            
                            @php
                                $attachments_aside_additional = array_filter($allSubmissionAttachments??[], function($excluded_attach) {
                                    return !str_contains($excluded_attach['label'],'Additional Attachment');
                                });
                            @endphp

                            @if (count($attachments_aside_additional) < count($checklist_items)) 
                                <li>Please attach the <strong>required documents</strong> before submitting your request.</li>
                            @endif 
                            @if (isset($fund_available) && $fund_available != $submissionRequest->amount_requested && (($submissionRequest->is_aip_request==true && (!str_contains(strtolower(optional($intervention)->name), 'teaching practice') && !str_contains(strtolower(optional($intervention)->name), 'conference attendance') && !str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship')) ) || ($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(optional($intervention)->name) && $submissionRequest->getParentAIPSubmissionRequest()==null) ))
           
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
                            
                            {{-- message for utilized fund belonging to non-ASTD interventions --}}
                            @if ($submissionRequest->is_aip_request==true && (!str_contains(strtolower(optional($intervention)->name), 'teaching practice') && !str_contains(strtolower(optional($intervention)->name), 'conference attendance') && !str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship')) && count($submission_allocations) > 0)
                                @foreach($submission_allocations as $allocation)
                                    @if($allocation->utilization_status != null && $allocation->utilization_status == 'utilized')
                                        <li>
                                            Allocated fund of <strong>&#8358;{{ number_format($allocation->allocated_amount, 2)}}</strong> for <strong>{{$allocation->year}} intervention year</strong> has been utilized.
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                            
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <form method="POST" action="{{ route('tf-bi-portal.submissionRequests.processSubmissionRequestToTFPortal', ['id'=>$submissionRequest->id]) }}" onSubmit="onSubmitAction()" id="form_final_submission">
                            @csrf
                            <input type="hidden" name="submission_request_id" value="{{ $submissionRequest->id }}">
                            <input type="hidden" name="checklist_items_count" value="{{ count($checklist_items) }}">
                            <input type="hidden" name="intervention_name" value="{{ $intervention->name}}">
                            <input type="submit" class="btn btn-sm btn-danger pull-right" value="Submit This Request"> 
                        </form>
                    </div>
                </div>
            @endif

            <div class="row">

                {{-- details and allocation preview modal --}}
                @include('tf-bi-portal::pages.submission_requests.partials.submission_details')

                {{-- sub menu buttons --}}
                @if(strtolower($submissionRequest->status) == 'submitted' || str_contains(strtolower(optional($intervention)->name), 'teaching practice') || str_contains(strtolower(optional($intervention)->name), 'conference attendance') || str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship'))
                    <div class="col-sm-12">
                        <div class="tab">
                            <ul class="nav nav-tabs nav-primary" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{(!isset(request()->sub_menu_items) || request()->sub_menu_items=="attachments")?'active':''}}" href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?sub_menu_items=attachments" >
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon">
                                                <i class="bx bx-paperclip font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Attachments</div>
                                        </div>
                                    </a>
                                </li>

                                @if(str_contains(strtolower(optional($intervention)->name), 'teaching practice') || str_contains(strtolower(optional($intervention)->name), 'conference attendance') || str_contains(strtolower(optional($intervention)->name), 'tetfund scholarship'))
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link {{(request()->sub_menu_items=="nominations_binded")?'active':''}}" href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?sub_menu_items=nominations_binded" >
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon">
                                                    <i class="bx bx-user-plus font-18 me-1"></i>
                                                </div>
                                                <div class="tab-title">Nominations</div>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                                @if(strtolower($submissionRequest->status) == 'submitted')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link {{(request()->sub_menu_items=="communications")?'active':''}}" href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}?sub_menu_items=communications" >
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon">
                                                    <i class="bx bx-layer-plus font-18 me-1"></i>
                                                </div>
                                                <div class="tab-title">Communications</div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                
                            </ul>
                        </div>
                    </div>
                @endif
                <hr>

                {{-- sub menu contents --}}
                @if(isset(request()->sub_menu_items) && request()->sub_menu_items == 'nominations_binded')
                    <div id="astd_nominations" class="col-sm-12 table-responsive">
                        {{-- <h4>NOMINATIONS LIST</h4> --}}
                        @include('tf-bi-portal::pages.submission_requests.partials.submission_nominations_table')
                    </div>
                @elseif(isset(request()->sub_menu_items) && request()->sub_menu_items == 'communications')
                    <div id="communications" class="col-sm-12">
                        {{-- <h4>COMMUNICATIONS</h4> --}}
                        @include('tf-bi-portal::pages.submission_requests.partials.submission_communications')
                    </div>    
                @else
                    <div id="attachments" class="col-sm-12">
                        {{-- <h4>ATTACHMENTS</h4> --}}
                        @include('tf-bi-portal::pages.submission_requests.partials.submission_attachments') 
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top  border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">Beneficiary Submission</h5></div>
        <p class="small text-justify">
            Please reveiw details on your submission to TETFund. Each submission requesting for intervention is processed at the front line department handling the intervention. The status is updated from here, and you may make follow up submissions or reprioritize your submission following AIP approval.
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