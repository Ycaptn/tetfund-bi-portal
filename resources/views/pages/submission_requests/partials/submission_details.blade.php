@php
    $get_all_related_requests = $submissionRequest->getAllRelatedSubmissionRequest();
    $years_str = $submissionRequest->intervention_year1;
    $all_monitoring_requests = $submissionRequest->getMonitoringSubmissionRequests();
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

            {{-- current intervention monitoring request --}}
            @if($submissionRequest->status=='submitted' && !empty($submitted_request_data) && $submissionRequest->is_aip_request==true && $submitted_request_data->has_generated_aip==true)
                @include('tf-bi-portal::pages.submission_requests.partials.monitoring_evaluation_submission_request')
            @endif

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


{{-- JS scripts --}}
@push('page_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        
        @if(isset($all_monitoring_requests) && count($all_monitoring_requests) > 0)

            // submit monitoring request to TETFund
            $(document).on('click', ".btn-submit-m-r", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                let itemId = $(this).attr('data-val');
                swal({
                    title: "You are about to process the final submission of this Monitoring Request to TETFund?",
                    text: "You will not be able to undo this process once completed.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, submit",
                    cancelButtonText: "No, don't submit",
                    closeOnConfirm: false,
                    closeOnCancel: true

                }, function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: '<div id="spinner-request-monitoring" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                            text: 'Please wait, validating requirements and submitting this monitoring request. <br><br> Do not refresh this page! ',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        });

                        let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.process_m_r_to_tetfund','') }}/"+itemId;

                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', 'POST');
                        formData.append('id', itemId);
                        
                        $.ajax({
                            url:endPointUrl,
                            type: "POST",
                            data: formData,
                            cache: false,
                            processData:false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result){
                                if(result.errors){
                                    console.log(result.errors)
                                    swal("Error", "Oops an error occurred. Please try again.", "error");
                                }else{
                                    swal({
                                        title: "Submission Completed",
                                        text: "Monitoring request submitted successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    });
                                    location.reload(true);
                                }
                            }, error: function(data) {
                                console.log(data);
                                swal("Error", "Oops an error occurred. Please try agaisssssn.", "error");
                            }
                        });
                    }
                });

            });

            // Show Modal for Edit
            $(document).on('click', ".btn-edit-m-r", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-request-for-monitoring-evaluation').fadeIn(300);
                    return;
                }else{
                    $('.offline-request-for-monitoring-evaluation').fadeOut(300);
                }

                $('#div-request-monitoring-evaluation-modal-error').hide();
                $('#frm-request-monitoring-evaluation-modal').trigger("reset");
                $('#new_or_old_m_r').text("Edit");
                $('#mdl-request-monitoring-evaluation-modal').modal('show');

                $("#spinner-request-monitoring-evaluation").show();
                $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', true);

                let itemId = $(this).attr('data-val');

                $.get( "{{ route('tf-bi-portal-api.submission_requests.show','') }}/"+itemId).done(function( response ) {     
                    console.log(response);
                    let proposed_monitoring_date= new Date(response.data.proposed_request_date).toISOString().slice(0, 10);

                    $('#m_r_primary_id').val(response.data.id);
                    $('#project_title').val(response.data.title);
                    $('#type_of_monitoring_request').val(response.data.type);
                    $('#proposed_monitoring_date').val(proposed_monitoring_date);

                    $('#editing_old_m_r_notice').show();
                    $("#spinner-request-monitoring-evaluation").hide();
                    $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', false);
                });
            });

            // Delete action for monitoring request
            $(document).on('click', ".btn-delete-m-r", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                let itemId = $(this).attr('data-val');
                swal({
                    title: "Are you sure you want to delete this Monitoring Request?",
                    text: "You will not be able to recover this request once deleted.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete",
                    cancelButtonText: "No, don't delete",
                    closeOnConfirm: false,
                    closeOnCancel: true

                }, function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: '<div id="spinner-request-monitoring" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                            text: 'Please wait, deleting this monitoring request <br><br> Do not refresh this page! ',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        });

                        let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.destroy','') }}/"+itemId;

                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', 'DELETE');
                        
                        $.ajax({
                            url:endPointUrl,
                            type: "POST",
                            data: formData,
                            cache: false,
                            processData:false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result){
                                if(result.errors){
                                    console.log(result.errors)
                                    swal("Error", "Oops an error occurred. Please try again.", "error");
                                }else{
                                    swal({
                                        title: "Deleted",
                                        text: "Monitoring request deleted successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    });
                                    location.reload(true);
                                }
                            },
                        });
                    }
                });

            });
        @endif
    });
</script>
@endpush