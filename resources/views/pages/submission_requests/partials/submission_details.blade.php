@php
    $get_all_related_requests = $submissionRequest->getAllRelatedSubmissionRequest();
    $years_str = $submissionRequest->intervention_year1;
    $all_monitoring_requests = $submissionRequest->getMonitoringSubmissionRequests();
    
    // merged years, unique years & sorted years
    if (isset($years) && count($years) > 1) {
        $years[count($years) - 1] = ' and ' . $years[count($years) - 1];
        $years_str = implode(", ", $years);
        $years_str = substr($years_str, 0,strrpos($years_str,",")) . $years[count($years) - 1];
    }

    // disbursement or draft-document record
    if(isset($submitted_request_data->response_documents_generated->{'AIP-Draft'})) {
        $approved_tranche_document = $submitted_request_data->response_documents_generated->{'AIP-Draft'};
    } elseif(isset($submitted_request_data->response_documents_generated->{'FirstTrancheDisbursement'})) {
        $approved_tranche_document = $submitted_request_data->response_documents_generated->{'FirstTrancheDisbursement'};
    } elseif(isset($submitted_request_data->response_documents_generated->{'Disbursement Memo'})) {
        $approved_tranche_document = $submitted_request_data->response_documents_generated->{'Disbursement Memo'};
    } else {
        $approved_tranche_document = null;
    }
@endphp

{{-- allocation preview modal --}}
<div class="modal fade" id="mdl-submissionRequestAllocationAmount-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-submissionRequestAllocationAmount-modal-title" class="modal-title">Preview Allocation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-sm-12">
                    <strong>SUBMISSION INTERVENTION YEAR(s) : </strong> [ {{ $years_str }} ] <hr>
                </div>
                <div class="row col-sm-12 m-3">
                    <div class="row table-responsive">
                        <table class="col-sm-12 table table-bordered">
                            <thead>
                                <tr>
                                    <th> S/N </th>
                                    <th> Year </th>
                                    <th> Type </th>
                                    <th> Allocation Code </th>
                                    <th> Allocated Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sn_counter = 0;
                                @endphp
                                @if(isset($submission_allocations) && count($submission_allocations) > 0)
                                    @foreach($submission_allocations as $sub_allocation)
                                        <tr>
                                            <td> {{ $sn_counter += 1 }}. </td>
                                            <td> {{ $sub_allocation->year }} </td>
                                            <td>    
                                                {{ ucwords($sub_allocation->type ?? $sub_allocation->intervention_beneficiary_type->type) }} - 
                                                {{ $sub_allocation->name ?? $sub_allocation->intervention_beneficiary_type->intervention->name ?? '' }} 
                                            </td>
                                            <td> {{ $sub_allocation->allocation_code }} </td>
                                            <td> &#8358; 
                                                {{ number_format((isset($sub_allocation->allocated_amount) ? $sub_allocation->allocated_amount : 0), 2) }} 
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                <tr>
                                    <td class="text-right" colspan="4" style="text-align: right;"><strong>Allocation(s) Sum Total <span class="fa fa-arrow-right"></span> </strong></td>
                                    <td><strong>&#8358; {{ number_format((isset($fund_available) ? $fund_available : 0), 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-submissionRequestAllocationAmount-modal">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12 {{ empty($get_all_related_requests) ? 'col-md-9' : 'col-md-6' }}">
        <div class="col-sm-12">
            @if(!empty($submitted_request_data))
                <i class="fa fa-briefcase fa-fw"></i> <b>File_Number:</b> &nbsp; {{ optional($submitted_request_data)->file_number }} <br/>
            @endif
            <i class="fa fa-calendar-o fa-fw"></i> <strong>Created on </strong> {{ \Carbon\Carbon::parse($submissionRequest->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($submissionRequest->created_at)->diffForHumans() !!} <br/>

            <i class="fa fa-bank fa-fw"></i> <b>{{ ucwords($intervention->type) }} Intervention &nbsp; - &nbsp; </b> &nbsp; {{ $intervention->name}} <br/>
            <i class="fa fa-briefcase fa-fw"></i> <b>Purpose of Request:</b> &nbsp; {{ $submissionRequest->type??'' }} <br/>
            <i class="fa fa-crosshairs fa-fw"></i> <b>Intervention Year(s) &nbsp; - &nbsp; </b> &nbsp; {{ $years_str }} <br/>

            {{-- ammount figures to be displayed based on ASTD and Non-ASTD interventions --}}
            @if($submissionRequest->is_astd_intervention($intervention->name)==true || ($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(optional($intervention)->name)))
            
                <hr style="margin: 0; border: none; border-top: 1px solid #000;">
                
                <i class="fa fa-money fa-fw"></i> <b>Total Allocated Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_allocated_fund : 0), 2) }} &nbsp; <b><i><small>({{ $allocations_details->allocation_end_year }} - {{ $allocations_details->allocation_start_year }})</small></i></b> <br/>

                <i class="fa fa-money fa-fw"></i> <b>Current Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_available_fund : 0), 2) }} <br/>
            
                <hr style="margin: 0; border: none; border-top: 1px solid #000;">
                
                
                <div style="padding-left:5px">
                    {{-- display when intervention is conference attendance --}}
                    @if(str_contains(strtolower($intervention->name), 'conference attendance'))
                        <i class="fa fa-arrow-right fa-fw"></i> <b>Academic Staffs Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_academic_staff_balance : 0), 2) }} <br/>
                        <i class="fa fa-arrow-right fa-fw"></i> <b>None-Academic Staffs Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_none_academic_staff_balance : 0), 2) }} <br/>

                    {{-- display when intervention is tetfunbd scholarship for academic staffs --}}
                    @elseif(str_contains(strtolower($intervention->name), 'tetfund scholarship'))
                            <i class="fa fa-arrow-right fa-fw"></i> <b>Local Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_local_balance : 0), 2) }} <br/>
                            <i class="fa fa-arrow-right fa-fw"></i> <b>Foreign Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_foreign_balance : 0), 2) }} <br/>
                            <i class="fa fa-arrow-right fa-fw"></i> <b>Post. Doc. Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_post_doc_balance : 0), 2) }} <br/>
                            <i class="fa fa-arrow-right fa-fw"></i> <b>Bench-Work Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($allocations_details) ? $allocations_details->total_work_bench_balance : 0), 2) }} <br/>
                    @endif
                </div>

                <hr style="margin: 0; border: none; border-top: 1px solid #000;">
                
                <i class="fa fa-money fa-fw"></i> <b>Total Amount Requested &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format($submissionRequest->amount_requested, 2) }} <br/>                

                @if(isset($allocations_details->total_available_fund))
                    <i class="fa fa-money fa-fw"></i> <b>Expected Balance After Disbursement &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format($allocations_details->total_available_fund - $submissionRequest->amount_requested, 2) }} <br/>
                @endif
            
                <hr style="margin: 0; border: none; border-top: 1px solid #000;">
            
            @else
                <i class="fa fa-money fa-fw"></i> <b>Total Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($fund_available) ? $fund_available : 0), 2) }} <br/>
                <i class="fa fa-money fa-fw"></i> <b>Amount Requested &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format($submissionRequest->amount_requested, 2) }} <br/>
            @endif

            <i class="fa fa-thumbs-up fa-fw"> </i><b>Current Stage &nbsp; - &nbsp; </b> &nbsp; {{ strtoupper($submitted_request_data->work_item->active_assignment->assigned_user->department->long_name ?? $submissionRequest->status) }}<br/>
            @if($submissionRequest->is_aip_request && !empty($submitted_request_data) && $submitted_request_data->request_status == 'reprioritized')
                <i class="text-danger">
                    <b>NOTE:</b> &nbsp; This submission was Reprioritized. <br/>
                </i>
            @endif

            {{-- notification for first tranche based interventions --}}
            @if($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(optional($intervention)->name))
                @php
                    $first_tranche_percentage = $submissionRequest->first_tranche_intervention_percentage(optional($intervention)->name);
                    $percentage = $first_tranche_percentage!=null ? str_replace('%', '', $first_tranche_percentage) : 0;
                    $percentage_amount = ($percentage * $submissionRequest->amount_requested)/100 ?? 0;
                @endphp

                @if($first_tranche_percentage!=null && $percentage_amount >= 0)
                    <i class="text-danger"><b>{{ $first_tranche_percentage }}</b> (₦{{ number_format($percentage_amount, '2') }}) of the <b>Requested Amount</b> is processed if validated successfully.</i> <br/> <br/>
                @endif
            @else
                <br/>
            @endif
            
            <div class="col-sm-12">
                <div class="row">
                    {{-- follow up submission request --}}
                   {{--  @if(($submissionRequest->status=='approved' || $submissionRequest->status=='submitted' || $submissionRequest->status=='recalled') && !empty($submitted_request_data) && $submitted_request_data->has_generated_aip==false && $submitted_request_data->has_generated_disbursement_memo==false)
                        @include('tf-bi-portal::pages.submission_requests.partials.follow_up_submission_request')
                    @endif --}}

                    {{-- Recall Submission request--}}
                    @if(($submissionRequest->status=='not-submitted' || $submissionRequest->status=='submitted' || $submissionRequest->status=='recalled') && 
                    ($submissionRequest->is_aip_request || $submissionRequest->is_first_tranche_request || $submissionRequest->is_second_tranche_request || $submissionRequest->is_third_tranche_request || $submissionRequest->is_final_tranche_request ) && 
                    !empty($submitted_request_data) && $submitted_request_data->has_generated_aip==false && $submitted_request_data->has_generated_disbursement_memo==false && ($submitted_request_data->request_status=='pending-recall'|| $submitted_request_data->request_status!='recalled'))
                        @include('tf-bi-portal::pages.submission_requests.partials.recall_submission_request')
                    @endif

                    {{-- current intervention monitoring request button --}}
                    @if(($submissionRequest->status=='approved' || $submissionRequest->status=='submitted' || $submissionRequest->status=='recalled') && !empty($submitted_request_data) && ($submitted_request_data->has_generated_aip==true || $submitted_request_data->has_generated_disbursement_memo==true) && $submissionRequest->monitoring_evaluation_interventions($intervention->name))
                        @include('tf-bi-portal::pages.submission_requests.partials.monitoring_evaluation_submission_request')
                    @endif

                    {{-- process repriotization for intervention request --}}
                    @if(($submissionRequest->status=='approved') && !empty($submitted_request_data) && $submitted_request_data->has_generated_aip==true && $submissionRequest->is_aip_request==true && empty($get_all_related_requests))

                        @include('tf-bi-portal::pages.submission_requests.partials.submission_request_reprioritization')
                    @endif

                    {{-- current intervention none AIP submission request  --}}
                    @if(($submissionRequest->status=='approved' || $submissionRequest->status=='submitted' || $submissionRequest->status=='recalled') && !empty($submitted_request_data) && (($submissionRequest->is_aip_request==true && $submitted_request_data->has_generated_aip==true) || ( ($submissionRequest->is_first_tranche_request==true || $submissionRequest->is_second_tranche_request==true || $submissionRequest->is_final_tranche_request==true) && $submitted_request_data->has_generated_disbursement_memo==true)))

                        @include('tf-bi-portal::pages.submission_requests.partials.submission_request_none_aip_tranches')
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (isset($get_all_related_requests) && !empty($get_all_related_requests))
        <div class="col-sm-12 col-md-3">
            <div class="col-lg-12 mb-2">
                <div class="list-group ">
                    <div class="p-1 list-group-item list-group-item-default">
                        <small>
                            <label class="text-center" style="border-bottom: 1px solid black; width:100%"><b>Related Requests</b></label><br>
                            @foreach($get_all_related_requests as $idx=>$request)
                                <a target="__new" href="{{ route('tf-bi-portal.submissionRequests.show', $request->id) }}" id="request_list" class="link-primary">
                                    <div class="d-flex align-items-center">
                                        <span class="fa fa-cube fa-fw"></span> 
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 pb-0">
                                                {{ ucwords($request->type) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach                                
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-sm-12 col-md-3">
        <div class="text-justify">     
            @if($submissionRequest->status=='submitted' || $submissionRequest->status=='approved')
                <span class="text-success pull-right"> 
                    <strong>
                        <span class="fa fa-check-square"></span>
                        Request {{ ucfirst($submissionRequest->status) }}
                    </strong> 
                </span><br>
            @elseif($submissionRequest->status=='recalled')
                <span class="text-primary pull-right"> 
                    <strong>
                        <span class="fa fa-redo"></span>
                        Request Recalled
                    </strong>
                </span><br>
            @else
                <span class="text-danger pull-right"> 
                    <strong>
                        <span class="fa fa-times"></span>
                        Request Not-Submitted
                    </strong>
                </span><br>
            @endif

            @if(($submissionRequest->status=='approved' || $submissionRequest->status=='submitted' || $submissionRequest->status=='recalled') && isset($submitted_request_data))
                @php
                    $dept_name = $submitted_request_data->work_item->active_assignment->assigned_user->department->long_name ?? $submitted_request_data->work_item->assignments[0]->assigned_user->department->long_name ?? '';
                @endphp
                
                <small>
                    @if(($submitted_request_data->has_generated_aip || $submitted_request_data->has_generated_disbursement_memo) && $submitted_request_data->request_status!='recalled')
                        <span class="text-success">
                            Please note that your <b>{{$submissionRequest->is_aip_request==true ? ($submissionRequest->is_astd_intervention($intervention->name)==true ? 'Request for Funding' : 'Approval-In-Principle (AIP)') : $submissionRequest->type.' Request' }}</b> has been completely processed{!! ucwords(' <b>@ TETFund ' . $dept_name . ' Department.</b>' ?? '.') !!}

                            @if($approved_tranche_document != null && $submissionRequest->is_aip_request==false)
                                <form action="{{route('display-response-attachment')}}" target="__blank" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="path" value="{{$approved_tranche_document->path}}">
                                    <input type="hidden" name="label" value="{{$approved_tranche_document->label}}">
                                    <input type="hidden" name="file_type" value="{{$approved_tranche_document->file_type}}">
                                    <input type="hidden" name="storage_driver" value="{{$approved_tranche_document->storage_driver}}">
                                    <button type="submit" class="btn btn-sm btn-success mt-2 pull-right" title="{{ $submissionRequest->type}} Disbursement Document">
                                        <span class="fa fa-envelope"></span> Disbursement Document.
                                    </button>
                                </form>
                            @else
                                You will be contacted for collection.
                            @endif
                        </span>
                    @else
                        <span class="text-danger"> 
                            Please note that your <b>{{$submissionRequest->is_aip_request==true ? ($submissionRequest->is_astd_intervention($intervention->name)==true ? 'Request for Funding' : 'Approval-In-Principle (AIP)') : $submissionRequest->type.' Request' }}</b> is currently being processed{!! ucwords(' <b>@ TETFund ' . $dept_name . ' Department.</b>' ?? '.') !!}
                            Once final approval is completed, you will be contacted for collection. 

                            @if($submitted_request_data->request_status=='pending-recall')
                                 &nbsp; Mean while, this request was <b>recalled</b> and currently pending approval.
                            @elseif($submitted_request_data->request_status=='recalled')
                                &nbsp; Mean while, the <b>recalling request</b> for this submission has been <b>approval</b>.
                            @endif
                        </span>
                    @endif          
                </small>
            @else
                <small class="text-danger">
                    Please note that your <b>{{$submissionRequest->is_aip_request==true ? ($submissionRequest->is_astd_intervention($intervention->name)==true ? 'Request for Funding' : 'Approval-In-Principle (AIP)') : $submissionRequest->type.' Request' }}</b> is yet to be submitted to TETFund.
                </small>
            @endif

            @if($submissionRequest->is_aip_request && (str_contains(strtolower($intervention->name), "physical infrastructure") || (str_contains(strtolower($intervention->name), "zonal intervention")) && str_contains($years_str, '2023')) && $submissionRequest->status=='not-submitted')
                <div class="col-sm-12 mt-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <b class="text-success">
                                <span class="fa fa-cubes fa-1x"></span> APPLICABLE REQUEST TYPE
                            </b>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">
                                <input type="checkbox" id="has_construction" value="construction" {{($has_construction_flag??'') ? "checked='checked" : ''}} >Construction
                            </label>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">
                                <input type="checkbox" id="has_procurement" value="procurement" {{($has_procurement_flag??'') ? "checked='checked" : '' }} >Procurement
                            </label>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- beneficiary request query/clarification response partial view --}}
    @include('tf-bi-portal::pages.submission_requests.partials.submission_request_queries')

    {{-- display list of related monitoring requests --}}
    @if(isset($all_monitoring_requests) && count($all_monitoring_requests) > 0)
        <div class="col-sm-12 mt-5">
            <div class="panel panel-default box box-info">
                <div class="panel-heading mediumText">
                    <strong>
                        <i class="fa fa-paper-plane"></i>
                        RELATED MONITORING REQUEST
                    </strong>
                </div>
                <div class="panel-body">
                    <table class="table table-striped text-center" >
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width:10%">#</th>
                                <th scope="col" style="width:40%">Project Title</th>
                                <th scope="col" style="width:15%">Proposed Date</th>
                                <th scope="col" style="width:15%">Status</th>
                                <th scope="col" style="width:10%">Attachment</th>
                                <th scope="col" style="width:10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_monitoring_requests as $monitoring_request)
                                <tr>
                                    <td scope="col">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td scope="col">
                                        <span>
                                            {{ $monitoring_request->title }}
                                        </span> <br> 
                                        <small>
                                            <i>{{ $monitoring_request->type }}</i>
                                        </small>
                                    </td>
                                    <td scope="col">                                          
                                        {{ date("M d, Y", strtotime($monitoring_request->proposed_request_date)) }}
                                    </td>
                                    <td scope="col" class="{{ $monitoring_request->status=='not-submitted'? 'text-danger' : 'text-success'}}">
                                        {{ ucwords(str_replace('-', ' ', $monitoring_request->status))}}
                                    </td>
                                    <td scope="col">
                                        @php
                                            $mr_attachment = $monitoring_request->get_all_attachments($monitoring_request->id)[0] ?? null
                                        @endphp
                                        @if($mr_attachment != null)
                                            <a href="{{ route('fc.attachment.show', $mr_attachment->id)}}" class="text-decoration-underline" target="__blank">
                                                Preview
                                            </a>
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td scope="col">
                                        @if ($monitoring_request->status=='not-submitted')
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <a href="#" class="col-sm-12 btn btn-sm btn-success btn-submit-m-r" data-val='{{$monitoring_request->id}}' title="Submit Monitoring Request">
                                                        <small>Submit</small>
                                                    </a>                                            
                                                    <a href="#" class="col-sm-12 col-md-6 btn btn-sm btn-default text-info btn-edit-m-r" data-val='{{$monitoring_request->id}}' title="Edit Monitoring Request">
                                                        <small>
                                                            <span class="fa fa-edit"></span>
                                                        </small>
                                                    </a>
                                                    <a href="#" class="col-sm-12 col-md-6 btn btn-sm btn-default text-danger btn-delete-m-r" data-val='{{$monitoring_request->id}}' title="Delete Monitoring Request">
                                                        <small>
                                                            <span class="fa fa-trash"></span>
                                                        </small>
                                                    </a>                                                
                                                </div>                                                
                                            </div>
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>