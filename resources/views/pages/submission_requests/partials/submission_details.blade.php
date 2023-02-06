@php
    $get_all_related_requests = $submissionRequest->getAllRelatedSubmissionRequest();
    $years_str = $submissionRequest->intervention_year1;
    // merged years, unique years & sorted years
    if (isset($years) && count($years) > 1) {
        if (count($years) > 1) {
            $years[count($years) - 1] = ' and ' . $years[count($years) - 1];
            $years_str = implode(", ", $years);
            $years_str = substr($years_str, 0,strrpos($years_str,",")) . $years[count($years) - 1];
        }
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
                                                {{ ucwords($sub_allocation->type) }} - 
                                                {{ $sub_allocation->allocation_type }} 
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
            <i class="fa fa-briefcase fa-fw"></i> <b>Requested Tranche:</b> &nbsp; {{ $submissionRequest->type }} <br/>
            <i class="fa fa-crosshairs fa-fw"></i> <b>Intervention Year(s) &nbsp; - &nbsp; </b> &nbsp; {{ $years_str }} <br/>
            <i class="fa fa-money fa-fw"></i> <b>Total Available Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($fund_available) ? $fund_available : 0), 2) }} <br/>
            <i class="fa fa-money fa-fw"></i> <b>Amount Requested &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format($submissionRequest->amount_requested, 2) }} <br/>
            <i class="fa fa-thumbs-up fa-fw"> </i><b>Current Stage &nbsp; - &nbsp; </b> &nbsp; {{ strtoupper($submitted_request_data->work_item->active_assignment->assigned_user->department->long_name ?? $submissionRequest->status) }}<br/><br/>

            {{-- current intervention none AIP submission request  --}}
            @if($submissionRequest->status=='submitted' && !empty($submitted_request_data) && (($submissionRequest->is_aip_request==true && $submitted_request_data->has_generated_aip==true) || ( ($submissionRequest->is_first_tranche_request==true || $submissionRequest->is_second_tranche_request==true || $submissionRequest->is_final_tranche_request==true) && $submitted_request_data->has_generated_disbursement_memo==true)))
                @include('tf-bi-portal::pages.submission_requests.partials.submission_request_none_aip_tranches')
            @endif
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
            @if($submissionRequest->status == 'submitted')
                <span class="text-success pull-right"> 
                    <strong>
                        <span class="fa fa-check-square"></span>
                        Request Submitted
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

            @if($submissionRequest->status == 'submitted' && isset($submitted_request_data))
                <small class="text-danger">
                    Please note that your <b>{{$submissionRequest->is_aip_request==true ? 'Approval-In-Principle (AIP)' : $submissionRequest->type.' Request' }}</b> is currently being processed{!! ucwords(' <b>@ TETFund ' . $submitted_request_data->work_item->active_assignment->assigned_user->department->long_name . ' Department.</b>' ?? '.') !!}
                        Once final approval is completed, you will be contacted for collection.               
                </small>
            @else
                <small class="text-danger">
                    Please note that your <b>{{$submissionRequest->is_aip_request==true ? 'Approval-In-Principle (AIP)' : $submissionRequest->type.' Request' }}</b> is yet to be submitted to TETFund.
                </small>
            @endif
        </div>
    </div>
</div>